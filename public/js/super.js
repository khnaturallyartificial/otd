$(document).ready(function(){
               $("#newsdate").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, showAnim:'slideDown'});
               $(".btn").button();
               $("#attachresult").hide();
               $(".removenews").click(function(){
                              var lolthis = this;
                              var newsid = ($(this).attr("id")).replace("rem","");
                              $.ajax({
                                             type:"POST",
                                             url:"/super/removenews/",
                                             data: "newsid="+newsid,
                                             success:function(result){
                                                            if(result == "success"){
                                                                           $(lolthis).parent().slideUp();
                                                            }
                                                            else{
                                                                           alert("Failed to delete news.");
                                                            }
                                             } 

                              });
               });
               $("#attachathletetocoach").click(function(){
                             var aid = ($("#AIDlist :selected").val()).replace("A", "");
                             var cid = ($("#CIDlist :selected").val()).replace("C", ""); 

                             $.ajax({
                                  type: "POST",
                                  url: "/super/doattach/",
                                  data: "aid="+aid+"&cid="+cid,
                                  success:function(result){
                                                 var txt = "";
                                                 if(result == "success"){
                                                                txt = "Successfully attach athlete `"+($("#AIDlist :selected").text())+"` to coach `"+($("#CIDlist :selected").text())+"`";
                                                 }
                                                 else{
                                                                txt = "Failed. Please contact the administrator";
                                                 }
                                                 $("#attachresult").stop().slideUp("fast").hide().html(txt).slideDown("fast").delay(4000).slideUp("fast");
                                                 $("#coachheader"+cid).parent().next().prepend('<li id="detach'+aid+'">'+($("#AIDlist :selected").text())+' <img src="/img/minus.png" class="unpin"/></li>')
                                  }
                             });
               });

               $(".unpin").live("click",function(){
                  var id =  ($(this).parent().attr("id")).replace("detach","");
                  $.ajax({
                                 type: "POST",
                                 url: "/super/dodetach/",
                                 data: "aid="+id,
                                 success:function(result){
                                              if(result == "success"){
                                                             $("#detach"+id).fadeOut("slow",function(){$(this).remove()});
                                              }
                                 }
                  });
               });

               $("#submitnews").click(function(){
                    var data = "date="+$("#newsdate").val() + "&heading="+$("#newsheading").val() + "&content="+ escape($("#newscontent").val());
                    $.ajax({
                         type: "POST",
                         url: "/super/addnews",
                         data: data,
                         success: function(result){
                                        if(result == "success"){
                                                       alert("News Added");
                                        }
                                        else{
                                                       alert("Failed! :(");
                                        }
                         }
                    });
               });
               $("#dialog-confirm").hide();
               $(".removeuserfromsystem").click(function(){
                   var userid = $(this).parent().attr("id");
                   var name = $(this).parent().children(".username").html();
                   $("#dialog-confirm").dialog({
                                  resizable:false,
                                  height:200,
                                  modal:true,
                                  buttons:{
                                                 "Delete" : function(){
                                                                $(this).dialog("close");
                                                                $.ajax({
                                                                    type: "POST",
                                                                    data: "userid="+userid,
                                                                    url: "/super/removeuser",
                                                                    success:function(result){
                                                                                   alert(result);
                                                                                   $("#"+userid).slideUp("fast");
                                                                    }
                                                                });
                                                 },
                                                 Cancel : function(){$(this).dialog("close")}
                                  }
                   })
               });
});