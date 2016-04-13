function my_onkeydown_handler() {
    switch (event.keyCode) {
        case 116 : // 'F5'
            event.preventDefault();
            event.keyCode = 0;
            window.status = "F5 disabled";
            break;
        case 82 : //R button
            if (event.ctrlKey){ 
                event.preventDefault();
                event.keyCode = 0;
                break;
            }
    }
}
document.addEventListener("keydown", my_onkeydown_handler);