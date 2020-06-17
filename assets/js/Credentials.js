function getLoginJS() {
    // Check if LocalStorage contains User_Email
    if(!(window.localStorage.getItem("User_Email") === null)) {
        window.location.href = "home.php";
    }
}