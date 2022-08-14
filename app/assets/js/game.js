function gameSelectors() {
    let playersBlock = document.getElementsByClassName('players')[0];
    let timeBlock = document.getElementsByClassName('time')[0];
    let minUsersField = document.getElementById('create_game_min_users');
    if (selectType.value == 'bigGame') {
        playersBlock.classList.add('d-none')
        timeBlock.classList.add('d-none')
        minUsersField.value = '';
        minUsersField.required = false;
        document.getElementById('create_game_time').value = '';
    } else if (selectType.value == 'simpleGame') {
        playersBlock.classList.remove('d-none')
        timeBlock.classList.remove('d-none')
        minUsersField.required = true;
    }
}

let selectType = document.getElementById('create_game_type');
selectType.addEventListener('change', function () {
    gameSelectors()
})

gameSelectors();