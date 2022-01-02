function changePromoteFormMessage(message, status) {
    let text = document.getElementById('promote-message')
    if (status === 200) {
        text.classList.remove('error-text')
        text.classList.add('success-text')
    } else {
        text.classList.remove('success-text')
        text.classList.add('error-text')
    }
    text.innerHTML = message
}

function deleteOptions() {
    document.querySelectorAll('.loaded').forEach(e => e.remove());
}

function addSkeletonPromoteModal() {
    document.querySelector('#promote-modal .modal-content').classList.add('skeleton')
}

//open
let promoteSelect = document.getElementById('promote-select')
let promoteModal = document.getElementById("promote-modal");
let promoteBtn = document.getElementById('promote')
promoteBtn.addEventListener('click', function () {
    promoteModal.style.display = "block";
    let domainName = window.location.origin
    let targetUserId = promoteBtn.dataset.target
    let promotionUrl = domainName + '/ajax/promotion/' + targetUserId + '/structs'
    let xhr = new XMLHttpRequest();
    xhr.open("GET", promotionUrl, true);
    xhr.onload = function (e) {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let structList = JSON.parse(xhr.response);
            for (let struct of structList) {
                let option = document.createElement('option')
                option.value = struct.id
                option.classList.add('loaded')
                option.innerHTML = `${struct.name} (${struct.city})`
                promoteSelect.append(option)
            }
            document.querySelector('#promote-modal .skeleton').classList.remove('skeleton')
        }
    };
    xhr.send();
})
//close
let closeBtn = document.getElementById("close-promote");
function closePromoteModal(className, timeDelay) {
    let timer = setTimeout(function () {
        promoteModal.style.display = "none";
        deleteOptions()
        addSkeletonPromoteModal()
        promoteModal.classList.remove('gone-success')
        promoteModal.classList.remove('gone-close')
        changePromoteFormMessage('')
        clearTimeout(timer-1);
    }, timeDelay)
    console.log(timer)
}
closeBtn.addEventListener('click', function () {
    promoteModal.classList.add('gone-close')
    closePromoteModal('gone-close', 0)
})
window.addEventListener('click', function (event) {
    if (event.target === promoteModal) {
        promoteModal.classList.add('gone-close')
        closePromoteModal('gone-close', 0)
    }
})
//submit
let submitPromoteBtn = document.getElementById('promote-submit')
submitPromoteBtn.addEventListener('click', function () {
    let domainName = window.location.origin
    let targetUserId = promoteBtn.dataset.target
    let structId = promoteSelect.options[promoteSelect.selectedIndex].value
    let promotionUrl = domainName + `/ajax/promotion/${structId}/${targetUserId}`
    let xhr = new XMLHttpRequest()
    xhr.open("GET", promotionUrl, true)
    xhr.onload = function (e) {
        if (xhr.readyState === 4 && xhr.status === 200) {
            changePromoteFormMessage(xhr.response, xhr.status)
            promoteModal.classList.add('gone-success')
            closePromoteModal('gone-success', 2500)
        } else {
            changePromoteFormMessage(xhr.response, xhr.status)
        }
    }
    xhr.send()
})