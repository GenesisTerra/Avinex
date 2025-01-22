function form_change(current, next) {
    document.getElementById(current).style.display = "none";
    document.getElementById(next).style.display = "block";
}

function navigateButton(destination) {
    window.location.href = destination;
}

function expandInput(event) {
    const textarea = event.target;
    textarea.style.height = "auto";
    textarea.style.height = textarea.scrollHeight + "px";
}

const inputField = document.querySelector(".expandable-input");
inputField.addEventListener("input", expandInput);
