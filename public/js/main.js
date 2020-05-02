var url = 'http://social-network.com.devel';
window.addEventListener("load", function(){
   
   $('.btn-like').css('cursor', 'pointer');
   $('.btn-dislike').css('cursor', 'pointer');
    
    //boton like
    function like(){
        $('.btn-like').unbind('click').click(function(){
            $(this).addClass('btn-dislike').removeClass('btn-like');
            $(this).attr('src', url+'/img/heart-red.png');
            
            $.ajax({
               url:  url+'/like/'+$(this).data('id'),
               type: 'GET',
               success: function(response){
                   if(response.like){
                       console.log('like');
                   }else {
                       console.log('no like');
                   }
               }
            });
            
            dislike();
        });
    }
    like();
    
   //boton dislike
   function dislike(){
        $('.btn-dislike').unbind('click').click(function(){
            $(this).addClass('btn-like').removeClass('btn-dislike');
            $(this).attr('src', url+'/img/heart-black1.png');
            
            $.ajax({
               url:  url+'/dislike/'+$(this).data('id'),
               type: 'GET',
               success: function(response){
                   if(response.like){
                       console.log('dislike');
                   }else {
                       console.log('no dislike');
                   }
               }
            });
            
            like();
        });
   }
   dislike();
   
   //buscador
   $('#buscador').submit(function(e){
		$(this).attr('action',url+'/gente/'+$('#buscador #search').val());
	});
   
});