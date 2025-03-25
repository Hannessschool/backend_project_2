//Hannes AJAX script
function processLike(ev)
{
    ev.preventDefault();
    // kolla ifall knappen funkar
    console.log(ev.target.value);

    let likedProfileId = ev.target.getAttribute("data-liked-profile-id");
    let receiverIdentifierId = ev.target.getAttribute("data-receiver-identifier-id");
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log("Successfully sent request");
            console.log(this.responseText);
            
            try {
                // Parseing av JSON responskod
                let response = JSON.parse(this.responseText);

                if (response.success) {
                    // dynamisk uppdatering av like count
                    document.querySelector("#likeCount_" + likedProfileId).textContent = response.new_like_count;
                } else {
                    alert("Gillandet av profilen misslyckades: " + response.message);
                }
            } catch (e) {
                console.error("Error parsing response:", e);
            }
        }
    };

    // Skicka outputen som en POST till `likes.php`
    xmlhttp.open("POST", "likes.php", true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send("liked_profile_id=" + likedProfileId + "&receiver_identifier_id=" + receiverIdentifierId);
}

// Lägg till event listener till alla like knappar
document.querySelectorAll('.likeButton').forEach(button => {
    button.addEventListener('click', processLike);
})

/*$(document).ready(function() {
    $('.messageForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        var receiverId = $(this).find('input[name="receiver_id"]').val();
        
        // Prepare the form data
        var formData = $(this).serialize(); 
        
        $.ajax({
            url: 'messages.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#messageResponse_' + receiverId).html(response);
                $('textarea[name="message"]').val('');
   
            },
            error: function(error) {
                $('#messageResponse_' + receiverId).html('Error: ' + error); // Display error message
            }
        });
            // Clear the message form after success
            $(this).find('textarea').val('');
    });
});
*/

//Lazy loading (ladda mer profiler)
document.addEventListener("DOMContentLoaded", function() {
    let offset = 0;
    const limit = 5;

    function loadAds(reset = false) {
        let formData = new FormData(document.getElementById("filterForm"));
        formData.append("offset", offset);
        formData.append("limit", limit);

        fetch("load_ads.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (reset) {
                document.getElementById("adsContainer").innerHTML = data;
            } else {
                document.getElementById("adsContainer").innerHTML += data;
            }

            offset += limit;

            if (!data.trim()) {
                document.getElementById("loadMore").style.display = "none";
            }
        });
    }

    document.getElementById("PING_IFRAME_FORM_DETECTION")?.remove();


    document.getElementById("filterForm").addEventListener("submit", function(e) {
        e.preventDefault();
        offset = 0;
        loadAds(true);
    });

    document.getElementById("loadMore").addEventListener("click", function() {
        loadAds();
    });

    loadAds();
});

