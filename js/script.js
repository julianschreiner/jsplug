// A $( document ).ready() block.
jQuery( document ).ready(function($) {
    console.log( "ready!" );

    $('.button-primary').click(function() {
        console.log("clicked");
        console.log(this.name);
        
        let roles = [];

        $('input[type=hidden].roles').each(function(){
            roles.push(this.name);
        });
        
        console.log(roles);
        
        let priceInformations = $('input[type=hidden][name=priceInformation'+ this.name +']').val();
        console.log(priceInformations);
        
        let template = '';
        if(priceInformations.length > 0){
            var obj = JSON.parse(priceInformations);
            console.log(obj);
            console.log(Object.keys(obj).length);
            var length = Object.keys(obj).length;

            if(length > 1){
                roles.forEach(function(element){
                    element = element.replace(/ /g,"_");
    
                    let tag = element + 'Price';
                    
                    let price = obj[tag];
                    
                
    
                    template += '<label for="'+ element +'Price">' + element + ' Price:' + '</label><input type="text" name="'+ tag +'" value="'+ price +'"><br>';
                });
            }
        }
        else{
            roles.forEach(function(element){
                let tag = element + 'Price';
                template += '<label for="'+ element +'Price">' + element + ' Price:' + '</label><input type="text" name="'+ tag +'"><br>';
            });
        }
       

        $('.appendix').remove();
        $('#post-' + this.name).after
            (
                '<tr class="appendix">' + 
                '<td class="column-name has-row-actions column-primary">' +
                '<form method="POST" action="">' +
                    template + 
                    '<br>' + 
                    '<input type="hidden" value="'+this.name+'" name="productID" ></input>' +
                    '<input type="submit" name="editPrices" class="button-primary" value="OK"></form>' +  
                    '</td>' +
                '</tr>' 
                
            )
    });


});