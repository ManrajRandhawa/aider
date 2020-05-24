function getUserEmail() {
    if(!(window.localStorage.getItem("User_Email") === null)) {
        return window.localStorage.getItem("User_Email");
    } else {
        return "Unknown User";
    }
}