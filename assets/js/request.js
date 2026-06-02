$('form').on('submit', function(e) {
    e.preventDefault();
    $(":submit", this).on("click", () => false)
    let data = new FormData(this),
        action = $(this).attr('action'),
        method = $(this).prop('method');

    axios({method: method, url: action, data: data})
    .then( (success) => {
        notify(success.data.message, success.data.title);
        window.location.reload();
    } )
    .catch( (e) => console.log("Error "+e) );
})
