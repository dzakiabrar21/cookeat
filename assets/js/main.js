const searchInput = document.getElementById('search');

console.log("main.js set");

searchInput.addEventListener('input', (event) => {
    let a = event.target.value; 
    load_search(a);
});

// function post(id) {

// }

function like(like, id) {
    console.log("liking");
    let not = like.children[1];
    let liked = like.children[0];
    fetch('././func.php?like=' + id)
    .then(response => response.text())
    .then(data => {
        not.classList.toggle("hidden");
        liked.classList.toggle("hidden");
        liked.innerHTML = '<span><i class="fas fa-heart"></i> '+data+'</span>';
        not.innerHTML = '<span><i class="far fa-heart"></i> ' +data +'</span>';
        // console.log("liked");
    })
    .catch(error => {
        console.error('Error:', error);
        return;
    });
}

function save(save, id) {
    let not_save = save.children[1];
    let saved = save.children[0];
    fetch('func.php?save=' + id)
    .then(response => response.text())
    .then(data => {
        // document.getElementById('log').innerHTML += data;
        not_save.classList.toggle("hidden");
        saved.classList.toggle("hidden");
        console.log("saved");
    })
    .catch(error => {
        console.error('Error:', error);
        return;
    });
}

function load_search(searchid) {
    if(searchid)
    document.getElementById('find').classList.remove("hidden");
    else document.getElementById('find').classList.add("hidden");
    fetch('././func.php?search=' + searchid)
        .then(response => response.text())
        .then(data => {
            document.getElementById('find').innerHTML = data;
        });
}
    

