    var PostsBtn = document.getElementById("posts-btn");
    var portfolioPostsContainer = document.getElementById("posts-container");

    if (PostsBtn) {
        PostsBtn.addEventListener("click", function() {
            var ourRequest = new XMLHttpRequest();
            ourRequest.open('GET', 'http://localhost:81/task/wp-json/task/v2/events?per_page=10&page=2');
            ourRequest.onload = function() {
                if (ourRequest.status >= 200 && ourRequest.status < 400) {
                    var data = JSON.parse(ourRequest.responseText);
                    createHTML(data);
                    PostsBtn.remove();
                } else {
                    console.log("We connected to the server, but it returned an error.");
                }
            };

            ourRequest.onerror = function() {
                console.log("Connection error");
            };

            ourRequest.send();
        });
    }

    function createHTML(postsData) {
        var ourHTMLString = '';
        for (i = 0; i < postsData.length; i++) {
            ourHTMLString += '<h4 class="uk-heading-divider" style="color:white;">' + postsData[i].title + '</h4>';
        }
        portfolioPostsContainer.innerHTML = ourHTMLString;
    }