import './app'

function searchProfile(e) {
 let valueInput = e.target.value;


 fetch('search/amin?recherche=' +valueInput, {
    method:"get"
}).then((response => {
    response.json().then((data) => {
       data.forEach(function() {
       //
       
           
       p =+ `<div class="suggestion"></div>`
       });
            
    
        
    })
}))




}

const input = document.querySelector("#search-user")
input.addEventListener("change", (e) => searchProfile(e))








