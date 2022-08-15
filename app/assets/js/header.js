let burger = document.getElementById('burger')
let menu = document.getElementById('menu-list')

burger.addEventListener('click', function () {
    burger.classList.toggle('open')
    if(burger.classList.contains('open')){
        menu.style.opacity = "1"
        menu.style.display = "block"
    } else {
        menu.style.opacity = "0"
        menu.style.display = "none"
    }
})