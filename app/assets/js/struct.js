let dots = document.getElementsByClassName('three-dots')

for (let dot of dots){
    dot.addEventListener('click', function (){
        let menu = dot.parentNode.querySelector('.menu');
        let menus = document.getElementsByClassName('show-anim');
        for (let menuEl of menus) {
            menuEl.classList.toggle('show-anim')
        }
            menu.classList.toggle('show-anim')
    })
}