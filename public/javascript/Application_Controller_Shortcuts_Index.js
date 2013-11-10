function toggle_fav(id) {
   var item = $('#fav_' + id);
   $.ajax({
     type: "GET",
     url: "/shortcutsfav/toggle/id/" + id + "/",
     dataType: "json",
     success: function(json) {
         if (json.type == 'success') {
            if (json.action == "off") {
               item.attr('class','shortcuts_favorite_icon_off');
            } else {
               item.attr('class','shortcuts_favorite_icon_on');
            }
         } else {
            alert("Error: " + json.message);
         }
     }
   });
}