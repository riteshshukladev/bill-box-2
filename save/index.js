document.addEventListener('DOMContentLoaded',function(){

    document.querySelector('.saveBtn').addEventListener('click',function(){
        var xhr = new XMLHttpRequest();
        var formData = new FormData();

        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
    })
})