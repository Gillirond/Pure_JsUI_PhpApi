
export let RequestService = {

    send: function(method, url, body, callback){
        let options = {
            method: method,
            headers: {
                'Content-Type': 'application/json; charset=utf-8'
            }
        };
        if(method.toUpperCase() !== 'GET') {
            options.body = JSON.stringify(body || {});
        }

        fetch(url, options)
            .then(response => response.json())
            .then(callback)
            .catch(error => {
                console.error('Fetch request failed. Error: ' + error.message);
            });
    }
};
