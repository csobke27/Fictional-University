import axios from "axios";

class MyNotes {
    constructor() {
        if(document.querySelector("#my-notes")){
            axios.defaults.headers.common['X-WP-Nonce'] = universityData.nonce;
            this.myNotes = document.querySelectorAll("#my-notes");
            this.events();
        }
        
    }

    events() {
        document.addEventListener("click", (e) => {
            if (e.target.classList.contains("delete-note") || e.target.closest(".delete-note")) {
                this.deleteNote(e);
            }
            if( e.target.classList.contains("edit-note") || e.target.closest(".edit-note")){
                this.editNote(e);
            }
            if( e.target.classList.contains("update-note") || e.target.closest(".update-note")){
                this.updateNote(e);
            }
            if( e.target.classList.contains("submit-note") || e.target.closest(".submit-note")){
                this.createNote(e);
            }
        });
    }

    slideUp(element, duration = 300) {
        element.style.overflow = 'hidden';
        element.style.transitionProperty = 'height, margin, padding';
        element.style.transitionDuration = duration + 'ms';
        element.style.height = element.offsetHeight + 'px';
        element.offsetHeight; // force reflow
        element.style.height = 0;
        element.style.paddingTop = 0;
        element.style.paddingBottom = 0;
        element.style.marginTop = 0;
        element.style.marginBottom = 0;
        setTimeout(() => element.remove(), duration);
    }

    slideDown(element, duration = 300) {
        element.style.removeProperty('display');
        let display = window.getComputedStyle(element).display;
        if (display === 'none') display = 'block';
        element.style.display = display;
        
        let height = element.offsetHeight;
        element.style.overflow = 'hidden';
        element.style.height = 0;
        element.style.paddingTop = 0;
        element.style.paddingBottom = 0;
        element.style.marginTop = 0;
        element.style.marginBottom = 0;
        element.offsetHeight; // force reflow
        
        element.style.transitionProperty = 'height, margin, padding';
        element.style.transitionDuration = duration + 'ms';
        element.style.height = height + 'px';
        element.style.removeProperty('padding-top');
        element.style.removeProperty('padding-bottom');
        element.style.removeProperty('margin-top');
        element.style.removeProperty('margin-bottom');
        
        setTimeout(() => {
            element.style.removeProperty('height');
            element.style.removeProperty('overflow');
            element.style.removeProperty('transition-duration');
            element.style.removeProperty('transition-property');
        }, duration);
    }

    deleteNote(e) {
        var thisNote = e.target.closest("li");
        // console.log("Delete note!", thisNote.dataset.id);
        axios.delete(universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.dataset.id)
        .then(response => {
            this.slideUp(thisNote);
            if(document.querySelector(".note-limit-message").classList.contains("active") && response.data.userNoteCount < response.data.userNoteLimit) {
                document.querySelector(".note-limit-message").classList.remove("active");
            }
            console.log("Note deleted successfully");
            console.log(response);
        })
        .catch(error => {
            console.log("Error deleting note:", error);
        });
    }

    updateNote(e) {
        var thisNote = e.target.closest("li");
        var updatedNote = {
            title: thisNote.querySelector(".note-title-field").value,
            content: thisNote.querySelector(".note-body-field").value
        }

        axios.post(universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.dataset.id, updatedNote)
        .then(response => {
            this.makeNoteReadOnly(thisNote);
            console.log("Note updated successfully");
            console.log(response);
        })
        .catch(error => {
            console.log("Error updating note:", error);
        });
    }

    editNote(e) {
        var thisNote = e.target.closest("li");
        if(thisNote.dataset.state == "read") {
            
            this.makeNoteEditable(thisNote);
            return;
        }
        this.makeNoteReadOnly(thisNote, true); // Pass true to indicate canceling
    }

    createNote(e){
        var title = document.querySelector(".new-note-title");
        var body = document.querySelector(".new-note-body");

        var newNote = {
            title: title.value,
            content: body.value,
            status: 'publish'
        }

        axios.post(universityData.root_url + '/wp-json/wp/v2/note/', newNote)
        .then(response => {
            // append the new note to the list
            if (this.myNotes) {
                var newNoteHTML = `
                <li data-id="${response.data.id}" data-state="read">
                    <input readonly value="${response.data.title.raw}" class="note-title-field">
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                    <textarea readonly class="note-body-field">${response.data.content.raw}</textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                </li>
                `;
                this.myNotes.insertAdjacentHTML('afterbegin', newNoteHTML);
                var newNoteElement = this.myNotes.firstElementChild;
                newNoteElement.style.display = 'none'; // Hide initially for slideDown
                this.slideDown(newNoteElement);
            }
            // reset form fields
            title.value = "";
            body.value = "";
            console.log("Note created successfully");
            console.log(response);
        })
        .catch(error => {
            if(error.response && error.response.status === 403 && error.response.data.message === "You have reached your note limit.") {
                document.querySelector(".note-limit-message").classList.add("active");
            }
            console.log("Error creating note:", error);
        });
    }

    makeNoteEditable(thisNote) {
        var titleField = thisNote.querySelector(".note-title-field");
        var bodyField = thisNote.querySelector(".note-body-field");
        
        // Store original values before editing
        thisNote.dataset.originalTitle = titleField.value;
        thisNote.dataset.originalContent = bodyField.value;
        
        thisNote.querySelector(".edit-note").innerHTML = '<i class="fa fa-times" aria-hidden="true"></i> Cancel';
        titleField.removeAttribute("readonly");
        bodyField.removeAttribute("readonly");
        titleField.classList.add("note-active-field");
        bodyField.classList.add("note-active-field");
        thisNote.querySelector(".update-note").classList.add("update-note--visible");
        titleField.focus();
        thisNote.dataset.state = "editable";
    }

    makeNoteReadOnly(thisNote, restoreOriginal = false) {
        var titleField = thisNote.querySelector(".note-title-field");
        var bodyField = thisNote.querySelector(".note-body-field");
        
        // Restore original values if canceling
        if(restoreOriginal) {
            titleField.value = thisNote.dataset.originalTitle;
            bodyField.value = thisNote.dataset.originalContent;
        }
        
        thisNote.querySelector(".edit-note").innerHTML = '<i class="fa fa-pencil" aria-hidden="true"></i> Edit';
        titleField.setAttribute("readonly", "readonly");
        bodyField.setAttribute("readonly", "readonly");
        titleField.classList.remove("note-active-field");
        bodyField.classList.remove("note-active-field");
        thisNote.querySelector(".update-note").classList.remove("update-note--visible");
        thisNote.dataset.state = "read";
    }
}

export default MyNotes;