(() => {
    "use strict";

    const braveSearchInput = document.querySelector('.brave-search-input');
    const searchType = document.querySelector('#search_type');
    const searchShortcutKey = document.querySelector('#brave_search-shortcut-key');
    const braveSearch = document.querySelector('#brave-search');
    const container = $(".brave-search-results-container");
    const nextPage = $("#next_page");
    const previousPage = $("#previous_page");
    const searchpageNo = $("#search_page_no");
    if (!braveSearchInput) return;

    let inputFocused = false;
    var timer = null;
    let currentPage = 1; // Keep track of the current page

    if (searchShortcutKey) {
        if (navigator.userAgent.indexOf('Mac OS X') != -1) {
            searchShortcutKey.innerText = 'cmd';
        } else {
            searchShortcutKey.innerText = 'ctrl';
        }
        searchShortcutKey.parentElement.classList.remove('opacity-0');
    }

    braveSearchInput.addEventListener('focus', function() {
        if (!onlySpaces(braveSearchInput.value)) {
            braveSearch.classList.add('done-searching');
            searchResultsVisible = true;
        }
    });

    braveSearchInput.addEventListener('keyup', function() {
        if (onlySpaces(braveSearchInput.value)) {
            searchResultsVisible = false;
            clearTimeout(timer);
            braveSearch.classList.remove('is-searching');
            braveSearch.classList.remove('done-searching');
        } else {
            braveSearch.classList.add('is-searching');
            clearTimeout(timer);
            timer = setTimeout(searchFunction, 1000);
        }
    });

    window.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.shiftKey || e.altKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            e.stopPropagation();
            if (inputFocused) return;
            braveSearchInput.focus();
            inputFocused = true;
            if (!onlySpaces(braveSearchInput.value)) {
                braveSearch.classList.add('done-searching');
                searchResultsVisible = true;
            }
        }
        if (e.key === 'Escape') {
            if (!inputFocused) return;
            braveSearchInput.blur();
            inputFocused = false;
            braveSearch.classList.remove('done-searching');
            searchResultsVisible = false;
        }
    });

    braveSearchInput.addEventListener('blur', () => {
        inputFocused = false;
    });

    document.addEventListener('click', ev => {
        const { target } = ev;
        const clickedOutside = braveSearch && !braveSearch.contains(target);
        if (clickedOutside) {
            braveSearch.classList.remove('is-searching');
            braveSearch.classList.remove('done-searching');
            searchResultsVisible = false;
        };
    });



    //sadece boşlukla arama mı yapmış
    function onlySpaces(str) {
        "use strict";
        return str.trim().length === 0 || str === '';
    }

    function resetSearch() {
        "use strict";
        document.getElementById("search_form").reset();
        return searchFunction('delete');
    }

    function searchFunction(page = 1) {
        const formData = new FormData();
        console.log(page);

        formData.append('_token', document.querySelector("input[name=_token]") && document.querySelector("input[name=_token]").value);

        // If 'page' is provided, it means we are fetching the next page of results

        formData.append('page', page); // Reset page to 1 for the initial search




        if (braveSearchInput.value.trim() === '') {
            // No need to perform an empty search
            return;
        }

        formData.append('keyword', braveSearchInput.value);
        formData.append('search_type', searchType.value);

        $.ajax({
            type: "POST",
            url: '/ar/dashboard/api/brave_search',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {


                container.empty(); // Clear existing results when performing a new search


                if (searchType.value == 'images' || searchType.value == "videos") {
                    container.append(response);

                } else if (searchType.value == 'search') {
                    container.append(response);

                } else {
                    var data = JSON.parse(response);

                    $.each(data.web.results, function(index, result) {
                        // Create a link for the website with logo and title
                        var websiteLink = $("<a class='flex flex-row'>")
                            .attr("href", result.url)
                            .attr("target", "_blank")
                            .append(
                                $("<img>").attr("src", result.meta_url.favicon).attr("alt", "Logo"),
                                $("<h2 class='px-4'>").text(result.title)
                            );

                        // Create a paragraph for the description
                        var description = $("<p>").html(result.description);

                        // Create a div to hold the individual result
                        var resultDiv = $("<div>").append(websiteLink, description);

                        // Append the result div to the container
                        container.append(resultDiv);
                    });
                }
                braveSearch.classList.remove('is-searching');
                braveSearch.classList.remove('done-searching');
                if (searchType.value !== "images") {
                    searchpageNo.html("Page " + page);
                    nextPage.show();
                    searchpageNo.show();
                    if (page == 1) {
                        previousPage.hide();
                    } else {
                        previousPage.show();
                    }
                }
            }
        });
    }


    function fetchNextPage() {
        // Increment the page number
        searchFunction(currentPage); // Fetch the next page
    }

    // Add event listeners for next and previous buttons
    nextPage.on("click", function() {
        currentPage++;
        fetchNextPage();
    });

    previousPage.on("click", function() {
        if (currentPage > 1) {
            currentPage--;
            fetchNextPage();
        }
    });

})();