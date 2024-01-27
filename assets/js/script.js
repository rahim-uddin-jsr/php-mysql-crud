
    console.log('object');
let links=document.querySelectorAll('.delete');
links.forEach(element => {
    element.addEventListener('click',(e)=>{
        !confirm('Are you sure?')&& e.preventDefault();
    })
});