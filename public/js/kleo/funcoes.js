

function kleo(action, id) {  
        $.post(
                '/cadastroKleo',
                {
                    action: action,
                    id: id                  
                },
        function (data) {
            if (data.response) {
                     location.href = data.url;                
            }
        }, 'json');    
}