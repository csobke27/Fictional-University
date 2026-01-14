import axios from "axios";

class Search {
    constructor() {
        this.addSearchHTML(); // Call the method to add search HTML to the page
        this.isOverlayOpen = false; // Initialize the overlay state
        this.openButtons = document.querySelectorAll(".js-search-trigger"); // Select all elements with the class
        this.closeButton = document.querySelector(".search-overlay__close");
        this.overlay = document.querySelector(".search-overlay");
        this.searchInput = document.querySelector("#search-term"); // Select the search input field
        this.typingTimer; // Timer for delayed function execution
        this.isSpinnerVisible = false; // Initialize spinner visibility state
        this.resultsContainer = document.querySelector(".search-overlay__results"); // Select the results container
        this.previousSearchVal; // Store the previous search value
        this.events();
    }

    events() {
        // Loop through all open buttons and attach the click event
        this.openButtons.forEach((button) => {
            button.addEventListener("click", () => this.openOverlay());
        });

        if (this.closeButton) {
            this.closeButton.addEventListener("click", () => this.closeOverlay());
        }

        document.addEventListener("keydown", (e) => this.keyPressHandler(e));

        if (this.searchInput) {
            this.searchInput.addEventListener("input", () => this.handleSearchInput());
        }
    }

    handleSearchInput() {
        const currentValue = this.searchInput.value; // Get the current value of the search input
        if (currentValue === this.previousSearchVal) return; // If the value hasn't changed, exit the function
        clearTimeout(this.typingTimer); // Clear the previous timer
        if(currentValue == ""){
            this.resultsContainer.innerHTML = ""; // Clear the results container if the input is empty
            this.isSpinnerVisible = false; // Set spinner visibility state to false
        } else {
            if (!this.isSpinnerVisible) {
                this.isSpinnerVisible = true; // Set spinner visibility state to true
                this.resultsContainer.innerHTML = "<div class='spinner-loader'></div>"; // Clear the results container
            }
            this.typingTimer = setTimeout(() => {
                this.performSearch(); // Call the search function after 2 seconds
            }, 750);
        }
    }

    performSearch() {
        const query = this.searchInput.value; // Get the current value of the search input
        // this.resultsContainer.innerHTML = `Performing search for: ${query}`;
        // Add your search logic here
        axios.get(universityData.root_url+'/wp-json/university/v1/search?term=' + this.searchInput.value)
        .then(response => {
            const data = response.data;
            this.resultsContainer.innerHTML = `
            <div class="row">
                <div class="one-third">
                    <h2 class="search-overlay__section-title">General Information</h2>
                    ${data.generalInfo.length ? `
                        <ul class="link-list min-list">
                            ${data.generalInfo.map(result => `<li><a href="${result.permalink}">${result.title}</a>${result.type == 'post' ? ` by ${result.authorName}` : ''}</li>`).join('')}
                        </ul>
                    ` : `
                        <div>No general information match that search.</div>
                    `}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Programs</h2>
                    ${data.programs.length ? `
                        <ul class="link-list min-list">
                            ${data.programs.map(result => `<li><a href="${result.permalink}">${result.title}</a></li>`).join('')}
                        </ul>
                    ` : `
                        <div>No programs match that search. <a href="${universityData.root_url}/programs">View all programs</a></div>
                    `}
                    <h2 class="search-overlay__section-title">Professors</h2>
                    ${data.professors.length ? `
                        <ul class="professor-cards">
                            ${data.professors.map(result => `
                            <li class="professor-card__list-item">
                                <a class="professor-card" href="${result.permalink}">
                                    <img class="professor-card__image" src="${result.landscapeImage}" alt="professor profile image">
                                    <span class="professor-card__name">${result.title}</span>
                                </a>
                            </li>`).join('')}
                        </ul>
                    ` : `
                        <div>No professors match that search.</div>
                    `}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Campuses</h2>
                    ${data.campuses.length ? `
                        <ul class="link-list min-list">
                            ${data.campuses.map(result => `<li><a href="${result.permalink}">${result.title}</a></li>`).join('')}
                        </ul>
                    ` : `
                        <div>No campuses match that search. <a href="${universityData.root_url}/campuses">View all campuses</a></div>
                    `}
                    <h2 class="search-overlay__section-title">Events</h2>
                    ${data.events.length ? `
                        ${data.events.map(result => `
                            <div class="event-summary">
                                <a class="event-summary__date t-center" href="${result.permalink}">
                                    <span class="event-summary__month">${result.month}</span>
                                    <span class="event-summary__day">${result.day}</span>
                                </a>
                                <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="${result.permalink}">${result.title}</a></h5>
                                <p>
                                    ${result.description}
                                    <a href="${result.permalink}" class="nu gray">Read more</a>
                                </p>
                                </div>
                            </div>
                        `).join('')}
                    ` : `
                        <div>No events match that search. <a href="${universityData.root_url}/events">View all events</a></div>
                    `}
                </div>
            </div>
            `;
            this.isSpinnerVisible = false; // Set spinner visibility state to false
        })
        .catch(() => {
            this.resultsContainer.innerHTML = "<div>Unexpected error; please try again later.</div>"; // Display an error message
            this.isSpinnerVisible = false; // Set spinner visibility state to false
        });
    }

    openOverlay() {
        this.resetSearch(); // Reset the search input and results
        this.overlay.classList.add("search-overlay--active"); // Add the active class to the overlay
        document.querySelector("body").classList.add("body-no-scroll"); // Prevent body from scrolling
        setTimeout(() => {
            this.searchInput.focus(); // Focus on the search input field
        }, 301);
        this.isOverlayOpen = true; // Set the overlay state to open
    }

    resetSearch(){
        this.searchInput.value = ""; // Clear the search input field
        this.resultsContainer.innerHTML = ""; // Clear the results container
        this.isSpinnerVisible = false; // Set spinner visibility state to false
        this.previousSearchVal = ""; // Reset the previous search value
    }

    closeOverlay() {
        this.overlay.classList.remove("search-overlay--active"); // Remove the active class from the overlay
        document.querySelector("body").classList.remove("body-no-scroll"); // Allow body to scroll again
        this.isOverlayOpen = false; // Set the overlay state to open
    }

    keyPressHandler(e) {
        // Check if the active element is an input or textarea
        const activeElement = document.activeElement;
        const isInputFocused = activeElement.tagName === "INPUT" || activeElement.tagName === "TEXTAREA";

        if (e.keyCode === 27 && this.isOverlayOpen && !isInputFocused) {
            this.closeOverlay();
        }
        if (e.keyCode === 83 && !this.isOverlayOpen && !isInputFocused) {
            this.openOverlay();
        }
    }

    addSearchHTML() {
        document.body.insertAdjacentHTML(
            "beforeend",
            `
            <div class="search-overlay">
            <div class="search-overlay__top">
                <div class="container">
                <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                <input type="text" id="search-term" class="search-term" placeholder="What are you looking for?" />
                <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                </div>
            </div>
            <div class="container">
                <div id="search-results" class="search-overlay__results">
                
                </div>
            </div>
            </div>`
        );
    }
}

export default Search;