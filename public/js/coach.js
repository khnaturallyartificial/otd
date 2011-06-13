$(window).load(function(){
            if(!$.browser.msie){
               $(".draggable").draggable();
            }
               $(".resizable").resizable();
               $("#addresult").hide();
});

$(document).ready(function(){
               $(".archiveit").click(function(){
                              var thisobj = $(this);
                              var planid = $(this).attr("alt");
                   $.ajax({
                                  type:"POST",
                                  data:"apid="+planid,
                                  url:"/coach/archiveplan/",
                                  success:function(result){
                                                 if(result == "success"){                                                                
                                                                //location.reload();
                                                                var plannamewoo = $("#"+planid).html();
                                                                $("#"+planid).parent().remove();
                                                                thisobj.next("br").remove();
                                                                thisobj.remove();
                                                                $("#inneralertmessage").html("Plan archived and removed from list.");
                                                                $("#alertmessage").slideDown(200);
                                                                $("#alertmessage").delay(4000).slideUp("slow");
                                                                $("#archivedlist").append('<span class="tipleft"><a class="planlist" id="'+planid+'" href="/coach/plans/planid/'+planid+'">'+plannamewoo+'</a></span><img src="/img/cross.png" class="deleteplanpermanent"><br>');
                                                 }
                                                 else{
                                                                alert("Failed to archive plan.");
                                                 }

                                  }
                   })
               });
               $("#hidemenu").click(function(){
	     var option = {}; 
	$("#menu").toggle("fold",option,500,function(){
	          $(".formenuhide").toggleClass("width150");
	})
               });

               $(".deleteplanpermanent").live("click",function(){
                   var url = $(this).prev(".tipleft").children(".planlist").attr("id");
                   window.location.href = "/coach/deleteplan/id/"+url;
               });
               $("#enddate").keyup(function(){
                              var valueofenddate = $(this).val();
                             if(valueofenddate !== ""){
                                             if(valueofenddate < 1 || valueofenddate > 14 || !valueofenddate.search(/^[0-9]+$/) == 0){

                                                            $(this).val(1);
                                             }
                             }
               });
               $("#togglethirdexercise").click(function(){
	$("#thirdexerciseid,#thirdexercisereps").slideToggle("fast");
	$("#exerciseid_com2,#reps_com2").attr("value","");
               });
               $(".loadplancompletion").click(function(){
                   var valueee   = $("#athlist :selected").val();
                   var va = valueee.split("-");
                   var athid = va[0];
                   var planid = va[1];
                   $.ajax({
                       type:"GET",
                       url:"/print/printplan2/planid/"+planid+"/userid/"+athid+"/formon/1/showcustom/1",
                       success:function(result){
                           var ress;
                           if(result.search(/error/) > -1){
                               ress = "Error! This athlete may not be attached to a plan.";
                           }
                           else{
                               ress = result;
                           }
                                      $("#plangrid").html(ress);
                                      $(".hide").hide();
                                      $(".markdone2, .markskipped2").remove();
                       }
                   });

                   $.ajax({
                                             type: "GET",
                                             url: "/print/printplan2/planid/"+planid + "/custom/1/hidetick/1/hascss/1/userid/"+athid,
                                             success: function(result){
                                                            //var res = result.split("<!-- break -->");
                                                            var ress;
                                                            if(result.search(/error/) > -1 || result.search(/setsfor/) == -1){
                                                                ress = "No plan to display...";
                                                            }
                                                            else{
                                                                ress = result;
                                                            }
                                                           $("#plangridcustom").html('                     <br/>\n\
                   <h2>Custom exercises in the past 7 days</h2>\n\
                   <br/>   '+ress);
                                                            //$("#printplanframe").html(res[1]+res[3]+res[5]);
                                                            //$("table .commentbox").hide();
                                                            $("*[title]").tipTip({defaultPosition:"top", delay:150, maxWidth:"300px", fadeIn:450 });
                                                            $(".hide").hide();
			$(".deleteexplan2").hide();
                                             }
                              });
               });

               $(".loadpastplancompletion").click(function(){
                   var valueee   = $("#athlist :selected").val();
                   var sd = $("#sd").val();
                   var ed = $("#ed").val();
                   var va = valueee.split("-");
                   var athid = va[0];
                   var planid = va[1];

                   $.ajax({
                                             type: "GET",
                                             url: "/print/printplan2/custom/1/planid/"+planid+"/hascss/1/userid/"+athid+"/startdate/"+sd+"/enddate/"+ed+"/past/1",
                                             success: function(result){
                                                            //var res = result.split("<!-- break -->");
                                                            var ress;
                                                            if(result.search(/error/) > -1 || result.search(/setsfor/) == -1){
                                                                //ress = "No plan to display...";
			    ress = result;
                                                            }
                                                            else{
                                                                ress = result;
                                                            }
                                                           $("#plangridcustom").html(ress);
                                                            //$("#printplanframe").html(res[1]+res[3]+res[5]);
                                                            //$("table .commentbox").hide();
                                                            $("*[title]").tipTip({defaultPosition:"top", delay:150, maxWidth:"300px", fadeIn:450 });
                                                            $(".hide,.markdone2,.markskipped2,.markfailed,.tickall").hide();
			$(".deleteexplan2").hide();
                                             }
                              });
               });
               $(".graphbtn,.btn").button();
               $(".graphbtn").click(function(){
                   var graphidname = ($(this).attr("id")).replace("_btn","");
                   $("#"+graphidname).slideToggle();
               });
               $("#hidegraphsection,#upload,#editbscj,.alertdiv").hide();
                $("#graph0_startdate, #graph0_enddate,#graph1_startdate, #graph1_enddate,#graph2_startdate, #graph2_enddate,#graph7_startdate, #graph7_enddate,#graph3_startdate, #graph3_enddate,#graph20_startdate, #graph20_enddate,#sd,#ed,#graph30_startdate, #graph30_enddate").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, showAnim:'slideDown'});
               $("#submitdailyreport, #setgraph0_params, #setgraph1_params, #setathleteid,#setgraph2_params,#display_summary, #setgraph3_params,#setgraph5_params,#setgraph20_params,#setgraph30_params").button();
               $("#setgraph7_params, #setgraph8_params,#setgraph9_params,#setgraph90_params,#savebscj").button();
               $("#revealeditbscj").click(function(){
                   $("#editbscj").slideToggle("fast");
               });
               if($("#athage").html() == ""){$("#changeprofileimage").hide();}
               $("#setgraph0_params").click(function(){
                              var uid = $("#userid").val();
                              var sdate = $("#graph0_startdate").val();
                              var edate = $("#graph0_enddate").val();
                              var limit = $("#graph0_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }
                              if(sdate && edate){
                                             imgsrc += "/sdate/"+sdate+"/edate/"+edate;
                              }

                              $("#chart0").attr('src',imgsrc);
               });
               $("#setathleteid").click(function(){
                              $("#hidegraphsection").slideDown("fast");
                              $("#userid").val($("#athlete_graph_list :selected").attr('value'));
                              $("#chart0").attr('src','/chart/index/mode/drawchart/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value'));
                              $("#chart1").attr('src','/drawchart_totallifted.php?limit=10&userid='+$("#athlete_graph_list :selected").attr('value'));
                              $("#chart3").attr('src','/chart/index/mode/drawchart_totalreps/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value'));
                              $("#chart2").attr('src','/chart/index/mode/drawchart_geninfo/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value')+"/sq/1/mr/1/pr/1/pte/1/ms/1/gf/1");
                              $("#chart5").attr('src','/chart/index/mode/drawchart_average/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value'));
	          $("#chart30").attr('src','/chart/index/mode/drawchart_averagedaily/limit/20/userid/'+$("#athlete_graph_list :selected").attr('value'));
                              //$("#chart6").attr('src','/chart/index/mode/drawchart_monotony/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value'));
                              $("#chart7").attr('src','/chart/index/mode/drawchart_sleepquan/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value'));
                              //$("#chart8").attr('src','/chart/index/mode/drawchart_ratio/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value'));
                              $("#chart9").attr('src','/chart/index/mode/drawchart_movements/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value')+
                                             '/snatch/1/psnatch/1/pc/1/cj/1/squat/1/pjfr/1');
	               $("#chart90").attr('src','/chart/index/mode/drawchart_movementspl/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value')+
                                             '/snatch/1/psnatch/1/pc/1/cj/1/squat/1/pjfr/1/fbp/1');
	               $("#chart20").attr('src','/chart/index/mode/drawchart_weightHydration/limit/10/userid/'+$("#athlete_graph_list :selected").attr('value')+
                                             '/bwm/1/bwe/1/um/1/ue/1');

               });
               $("#setgraph1_params").click(function(){
                              var uid = $("#userid").val();
                              var sdate = $("#graph1_startdate").val();
                              var edate = $("#graph1_enddate").val();
                              var limit = $("#graph1_limit").val();
                              var imgsrc = "/drawchart_totallifted.php?userid="+uid;

                              if(limit){
                                             imgsrc += "&limit="+limit;
                              }
                              else{
                                             imgsrc += "&limit=10";
                              }
                              if(sdate && edate){
                                             imgsrc += "&sdate="+sdate+"&edate="+edate;
                              }

                              $("#chart1").attr('src',imgsrc);
               });
               $("#setgraph20_params").click(function(){
                              var uid = $("#userid").val();
                              var sdate = $("#graph20_startdate").val();
                              var edate = $("#graph20_enddate").val();
                              var limit = $("#graph20_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_weightHydration/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }
                              if(sdate && edate){
                                             imgsrc += "/sdate/"+sdate+"/edate/"+edate;
                              }
                              $(".bh:checked").each(function(){
                                             imgsrc += "/"+$(this).attr("id") +"/1";
                              });

                              $("#chart20").attr('src',imgsrc);
               });
               $("#setgraph3_params").click(function(){
                              var uid = $("#userid").val();
                              var sdate = $("#graph3_startdate").val();
                              var edate = $("#graph3_enddate").val();
                              var limit = $("#graph3_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_totalreps/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }
                              if(sdate && edate){
                                             imgsrc += "/sdate/"+sdate+"/edate/"+edate;
                              }

                              $("#chart3").attr('src',imgsrc);
               });
               $("#setgraph5_params").click(function(){
                              var uid = $("#userid").val();
                              var sdate = $("#graph5_startdate").val();
                              var edate = $("#graph5_enddate").val();
                              var limit = $("#graph5_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_average/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }
                              if(sdate && edate){
                                             imgsrc += "/date/"+sdate+"/edate/"+edate;
                              }

                              $("#chart5").attr('src',imgsrc);
               });
               $("#setgraph30_params").click(function(){
                              var uid = $("#userid").val();
                              var sdate = $("#graph30_startdate").val();
                              var edate = $("#graph30_enddate").val();
                              var limit = $("#graph30_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_averagedaily/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }
                              if(sdate && edate){
                                             imgsrc += "/sdate/"+sdate+"/edate/"+edate;
                              }

                              $("#chart30").attr('src',imgsrc);
               });
               $("#setgraph2_params").click(function(){
                              var uid = $("#userid").val();
                              var sdate = $("#graph2_startdate").val();
                              var edate = $("#graph2_enddate").val();
                              var limit = $("#graph2_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_geninfo/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }
                              if(sdate && edate){
                                             imgsrc += "/sdate/"+sdate+"/edate/"+edate;
                              }

                              $("input:checked").each(function(){
                                             imgsrc += "/"+$(this).attr("id") +"/1";
                              });
                              $("#chart2").attr('src',imgsrc);
               });
               /*$("#setgraph6_params").click(function(){
                              var uid = $("#userid").val();
                              var limit = $("#graph6_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_monotony/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }

                              $("#chart6").attr('src',imgsrc);
               });
	*/
               $("#setgraph7_params").click(function(){
                              var uid = $("#userid").val();
                              var sdate = $("#graph7_startdate").val();
                              var edate = $("#graph7_enddate").val();
                              var limit = $("#graph7_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_sleepquan/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }
                              if(sdate && edate){
                                             imgsrc += "/sdate/"+sdate+"/edate/"+edate;
                              }

                              $("input:checked").each(function(){
                                             imgsrc += "/"+$(this).attr("id") +"/1";
                              });
                              $("#chart7").attr('src',imgsrc);
               });
               /*$("#setgraph8_params").click(function(){
                              var uid = $("#userid").val();
                              var limit = $("#graph8_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_ratio/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }

                              $("#chart8").attr('src',imgsrc);
               });*/

               $("#setgraph9_params").click(function(){
                              var uid = $("#userid").val();
                              var limit = $("#graph9_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_movements/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }

                              $("#movementgraphparams input:checked").each(function(){
                                             imgsrc += "/"+$(this).attr("id") +"/1";
                              });
                              $("#chart9").attr('src',imgsrc);
               });
               $("#setgraph90_params").click(function(){
                              var uid = $("#userid").val();
                              var limit = $("#graph90_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_movementspl/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }

                              $("#movementplgraph input:checked").each(function(){
                                             imgsrc += "/"+$(this).attr("id") +"/1";
                              });
                              $("#chart90").attr('src',imgsrc);
               });

               $(".viewSCJ").click(function(){
	     $("#colgraph").attr("src","/chart/index/mode/drawchart_correlation/type/SCJ");
               });
               $(".viewSSS").click(function(){
	  $("#colgraph").attr("src","/chart/index/mode/drawchart_correlation/type/SS");
               });
               $(".viewData").click(function(){
	if(($("#datatable").html()).length > 0){
	          $("#datatable").html("");
	}
	else{
	$.ajax({
	        type:"POST",
	        data:"",
	        url:"/coach/makemaxtable/",
	        success:function(result){
	                  $("#datatable").html(result);
	        }
	})     ;
	}
               });
               $("#attachresult, .athmenu").hide();
	$("#startdate").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, showAnim:'slideDown'});
               $("#comment").keypress(function(event){
                              if(event.keyCode == '13'){
                                             if($("#weight").val() != '' && $("#sets").val() != '' && $("#reps").val() != ''){
                                                            $("#addexerciseplan").click();
                                             }
                              }
               });
               $("#reps").focus(function(){$(this).val("1").select();});
               $("#sets, label[for=\"sets\"]").hide();
               //$("#sets").keypress(function(event){if(event.keyCode == '13'){$("#comment").focus();}});
               //$("#weight").keypress(function(event){if(event.keyCode == '13'){$("#reps").focus();}});
               //$("#reps").keypress(function(event){if(event.keyCode == '13'){$("#sets").focus();}});
               $(".cviewath").mouseenter(function(){$(this).find(".athmenu").fadeIn("fast")})
                             .mouseleave( function(){$(this).find(".athmenu").fadeOut("fast")});

               $("#addexerciseplan").click(function(){
                              $("#addresult").stop(true,true).slideUp("fast");
                              if($("#comment").val()){
                                             var comment = '&comment='+ ($("#comment").val()).replace('&','~');
                              }
                              else {
                                             var comment = '';
                              }
			      if($("#am_or_pm").is(":checked")){
				  var apval = "1";
			      }
			      else{
				  var apval = "0";
			      }
                              $.ajax({
                                             type: "POST",
                                             url: "/coach/addexerciseplan",
                                             data: "planid="+$("#planid").val()+"&date="+$("#date").val()+"&exerciseid="+$("#exerciseid").val()+"&weight="+$("#weight").val()+"&reps="+$("#reps").val()+"&sets="+$("#sets").val()+"&exerciseid_com="+$("#exerciseid_com").val() + "&reps_com="+$("#reps_com").val()+"&exerciseid_com2="+$("#exerciseid_com2").val() + "&reps_com2="+$("#reps_com2").val() +"&am_or_pm="+apval +  comment,
                                             success: function(result){
                                                            var rsplit = result.split("||");
                                                            $("#addresult").hide().html(rsplit[2]).slideDown("fast").delay(3000).slideUp("slow");
                                                            if(result.search(/success/i)>1){
                                                                           var prependString = "";
                                                                           prependString += $("#exerciseid :selected").text() + ' - ';
                                                                           prependString += $("#weight").val() + 'kg. x' + $("#reps").val() + ' : ' + $("#sets").val() + ' sets. **'+ $("#comment").val();
                                                                           $("#comment").val("");
                                                                           $("#weight").focus();

                                                                           /*check for date heading, create if not exist */
                                                                           if($("#"+$("#date").val()).length <1){
                                                                                          var title = $("#date :selected").text();
                                                                                          title = title.replace(" - ", "(") + ")";
                                                                                          var titleid = $("#date :selected").val();
                                                                                          var preparedtext = '<a id="'+titleid+'"><img src="/img/calendar-today.png" alt="*">';
                                                                                          preparedtext += '<span class="plantitle rounded3">&nbsp;'+title+'&nbsp;</span></a><ul></ul>';
                                                                                          $("#exercisesinthisplan").prepend(preparedtext);
                                                                                          $("#"+$("#date :selected").val()).hide().slideDown("slow");
                                                                           }

                                                                           $("#"+$("#date").val()).next().prepend("<li id=\"epid"+rsplit[0]+"\">"+prependString+"<img src=\"/img/cross.png\" alt=\"-\" class=\"deleteexplan\" /><img src=\"/img/new.png\" alt=\"\" /></li>");
                                                                           $("#epid"+rsplit[0]).hide().slideDown(1000);
                                                                           
                                                                          
                                                            }
                                                            $(".refreshprintplan").click();
                                             }
                              });

                              
               });

               $(".refreshprintplan").click(function(){
                              $.ajax({
                                             type: "GET",
                                             url: "/print/printplan2/planid/"+$("#planid").val(),
                                             success: function(result){
                                                            //var res = result.split("<!-- break -->");
                                                           $("#printplanframe").html(result);
                                                            //$("#printplanframe").html(res[1]+res[3]+res[5]);
                                                            //$("table .commentbox").hide();
                                                            $("*[title]").tipTip({defaultPosition:"top", delay:150, maxWidth:"300px", fadeIn:450 });
                                                            $(".hide").hide();
			$("#totvol").html($("#totrep").val());
			$("#totav").html($("#totweight").val() / $("#totrep").val());
                                             }
                              });
                              
                              
               });
               $(".deleteexercise").live("click",function(){
                   var exerciseid = $(this).attr("id");
                   $.ajax({
                                  type: "POST",
                                  data: "id="+exerciseid,
                                  url: "/coach/delex",
                                  success: function(result){
                                                 alert(result);
                                                 location.reload();

                                  },
                                  error:function(result){
                                                 alert("Failed to add the exercise. Exercise with the same number may already exist.");
                                  }
                   });
               });

               $("#addexercise").click(function(){
                              if($("#exerciseidinput").val()){
                              $.ajax({
                                             type: "POST",
                                             url: "/coach/exercise",
                                             data: "e=" + $("#exercisenameinput").val() + "&id="+$("#exerciseidinput").val()+"&type="+$("input:radio:checked").val(),
                                             error:function(result){alert("Exercise with this number already exist, please choose a different number")},
                                             success: function(result){
                                                            $(".aestatus").hide().html(result).slideDown(300);
                                                            $.ajax({
                                                                           type: "POST",
                                                                           url: "/coach/exercise",
                                                                           data: "newexerciselist=true",
                                                                           success: function(result2){
                                                                                          $("#exerciselist").slideUp(300)
                                                                                          .delay(300)
                                                                                          .html(result2).slideDown(300);
                                                                           }
                                                            });
                                                            $(".aestatus").delay(4000).slideUp(600);
                                             }
                              });
                              return false;
                              }
                              else{
                                             alert("Please don't forget the Exercise Number.");
                              }
               });
               $(".revalert").click(function(){
                   $(this).parent().next("div").slideToggle("fast");
               });
               $(".getathinfo").click(function(){
	     var uid = $(this).val();
	$.ajax({
	   type:"POST",
	   data:"uid="+uid,
	   url:"/coach/getathinfo/",
	   success:function(result){
	             var split = result.split("|");
	             $(".msg").html('<img src="/userpicture/'+split[2]+'"/><br/><br/>'+split[0]+'<br/>e-mail: <a href="mailto:'+split[1]+'">'+split[1]+'</a>').dialog({maxWidth:400,buttons: { "Close": function() { $(this).dialog("close"); } } ,resizable:false});
	   }
	});
               });
               $("#changeplaninfo").click(function(){
                              
                    $.ajax({
                                   type: "POST",
                                   url:"/coach/updateplan/",
                                   data: "planname="+($("#planname").val()).replace('&','/')+"&startdate="+$("#startdate").val()+
                                                  "&enddate="+$("#enddate").val()+"&planid="+$("#planid").val(),
                                   success:function(result){
                                                  location.reload();
                                   }
                    });
                    
               });
               //pending for deletion
               $(".deleteexplan").live('click',function(){

                             var idtodelete = ($(this).parent().attr("id")).replace("epid","");

                             $.ajax({
                                            type: "POST",
                                            url:"/coach/deleteexerciseplan/",
                                            data:"deleteid="+idtodelete,
                                            success:function(result){
                                                           if(result.search(/deleted/i)){
                                                                          $("#epid"+idtodelete).slideUp(700);
                                                           }
                                            }
                             });

               });
               ///////////////////////////////
               $(".deleteexplan2").live('click',function(){

                             var idtodelete = ($(this).parent().attr("class")).replace("epid","");

                             $.ajax({
                                            type: "POST",
                                            url:"/coach/deleteexerciseplan/",
                                            data:"deleteid="+idtodelete,
                                            success:function(result){
                                                           if(result.search(/deleted/i)){
                                                                          $(".epid"+idtodelete).html("");
			              $(".refreshprintplan").click();
                                                           }
                                            }
                             });

               });

               $("#attachathletebtn").click(function(){
                              var atid = $("#athleteselection :selected").val().replace("athlete","");
                    $.ajax({
                                   type: "POST",
                                   url:"/coach/attachathlete",
                                   data:"ath="+atid+"&plan="+ $("#planid").val(),
                                   success:function(result){
                                                  $.ajax({
                                                                 type:"GET",
                                                                 url:"/coach/planathletelist",
                                                                 data:"planid="+$("#planid").val(),
                                                                 success:function(res){
                                                                                $("#athletelist").slideUp("fast").delay(700).html(res).slideDown("fast");
                                                                 }
                                                  });
                                   }
                    })          
               });

               $(".removeathfrmlst").live('click', function(){
                    var idtorem= ($(this).parent().attr("id")).replace("at", "");
                    $.ajax({
                                   type:"POST",
                                   url:"/coach/detachathlete",
                                   data:"aid="+idtorem,
                                   success:function(res){
                                                  $("#at"+idtorem).slideUp("fast").delay(800).hide().remove();
                                   }
                    });
               });

               $("#doattach").click(function(){
                             var aid = $("#AIDlist :selected").val();

                             $.ajax({
                                  type: "POST",
                                  url: "/coach/doattach/",
                                  data: "aid="+aid,
                                  success:function(result){
                                                 location.reload();
                                                 
                                  }
                             });
               });
               $(".athmenu3").click(function(){
                  var aid =  ($(this).attr('id')).replace('cviewath','');
                  var self = this;
                 $.ajax({
                    type: "POST",
                    url:"/coach/detachfromme",
                    data: "aid="+aid,
                    success: function(result){
                        if(result == "success"){
                            $(self).parent().parent().fadeOut("fast");
                        }
                        else{

                        }
                    }
                 });
               });
               $("#changeprofileimage").click(function(){
                    $("#upload").slideToggle();
               })

               $("#display_summary").click(function(){
                    var aid = $("#athlete_summary_list :selected").val();
                    $("#useridh").val(aid);
                    $.ajax({
                                   type: "POST",
                                   url:"/coach/fetchathleterow",
                                   data: "aid="+aid,
                                   success: function(result){
                                                  $("#changeprofileimage").slideDown();
                                                 var info = result.split("||");
                                                 $("#athname").html(info[0]);
                                                 $("#athage").html(info[1] );
                                                 $("#todaydate").html(info[3]);
                                                 $("#athheight").html(info[4]);
                                                 $("#athweight").html(info[5]);
                                                 $("#athcomments").html(info[6]);
                                                 $("#athmaxsnatch").html(info[7]);$("#editms").val(info[7]);
                                                 $("#athcj").html(info[8]);$("#editcj").val(info[8]);
                                                 $("#athbodyfat").html(info[9]); $("#editbf").val(info[9]);
                                                 $("#athmcrL").html(info[10]);
                                                 $("#athmcrR").html(info[11]);
                                                 $("#profileimage").attr("src", "/userpicture/"+info[12]);
                                                 $("#athinjuries").html(info[13]);
                                                 $("#athinjurystatus").html('<span class="rounded3 status'+info[14]+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/><a title="'+info[15]+"<br/>"+info[16]+'">Last 30 days...</a>');
                                                 $("*[title]").tipTip({defaultPosition:"top", delay:150, maxWidth:"300px", fadeIn:450 });

                                   }
                    });
               });
               $("#savebscj").click(function(){
                   var data = "bf="    +$("#editbf").val() + "&maxsnatch=" +$("#editms").val() + "&maxcj="+ $("#editcj").val() + "&aid=" + $("#useridh").val();
                   $.ajax({
                       type: "POST",
                       data: data,
                       url: "/coach/updateathsum",
                       success: function(result){
                                     if(result == "success"){
                                                    $("#editbscj").slideUp("fast");
                                                    alert("Info saved. Click display button again to refresh the data.");

                                     }
                                     else{
                                                   alert("failed to save data...");
                                     }
                       }
                   });
               });
$(".refreshprintplan").click();
$("#exercisesinthisplan").hide();
$(".commentbox").hide();
               $("#athletelisthistory").change(function(){
                              //alert($(this).val());
                    window.location = "/coach/history/atid/"+ $("#athletelisthistory option:selected").val();
               });
               $("#saveemailbtn").click(function(){
                             var dataa = "email="+$("#emailaddress").val();
                             $.ajax({
                                 type: "POST",
                                 url: "/coach/saveemail",
                                 data: dataa,
                                 success: function(result){
                                                alert(result);
                                 }
                             });
               });
               $("#exerciseid_com_div,#reps_com_div,#thirdexerciseid,#thirdexercisereps").hide();
               $(".combinedexercisebtn").click(function(){
                              $("#exerciseid_com,#reps_com").attr("value","");
	          $("#exerciseid_com2,#reps_com2").attr("value","");
                              $("#exerciseid_com_div,#reps_com_div").slideToggle("fast");
               })
});