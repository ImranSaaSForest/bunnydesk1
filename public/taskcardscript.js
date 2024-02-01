


function allowDrop(ev) {
    console.log(ev);
    ev.preventDefault(); // default is not to allow drop
}

function dragStart(ev, n) {
    ev.dataTransfer.setData("text/plain", ev.target.id, +"," + n);
}

function dropIt(ev) {
    ev.preventDefault(); // default is not to allow drop
    let sourceId = ev.dataTransfer.getData("text/plain");
    let sourceIdEl = document.getElementById(sourceId);
    let sourceIdParentEl = sourceIdEl.parentElement;
    let targetEl = document.getElementById(ev.target.id)
    // console.log(targetEl.id);
    let targetParentEl = targetEl.parentElement;
    if (targetParentEl.id !== sourceIdParentEl.id) {
        console.log('no3');
        if (targetEl.className === sourceIdEl.className) {
            let parentID = targetEl.parentElement;
            console.log('no1');
            Livewire.dispatch('post-created', {
                postId: sourceId,
                id: parentID.id
            })
            console.log(targetEl.id);
            targetParentEl.appendChild(sourceIdEl);

        } else {
            console.log('no2');
            Livewire.dispatch('post-created', {
                postId: sourceId,
                id: targetEl.id
            })
            targetEl.appendChild(sourceIdEl);
        }
    } else {
        // console.log('no');
        // let holder = targetEl;
        // let holderText = holder.textContent;
        // targetEl.textContent = sourceIdEl.textContent;
        // sourceIdEl.textContent = holderText;
        // holderText = '';
    }
}

