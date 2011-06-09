$(document).ready(function(){

               $("#charttest").click(function(){
                    $("#chart0").attr('src', '/drawchart.php?userid=2&limit=20');
               });
               $(".clearex2").click(function(){
	$("#exerciseid_com,#exerciseid_com2,#reps_com,#reps_com2").val("");
               });
               $(".clearex3").click(function(){
	$("#exerciseid_com2,#reps_com2").val("");
               });
               $("#graph0_startdate, #graph0_enddate,#graph1_startdate, #graph1_enddate,#graph20_startdate, #graph20_enddate,#graph30_startdate, #graph30_enddate, #graph7_startdate, #graph7_enddate,#graph2_startdate, #graph2_enddate,#graph3_startdate, #graph3_enddate,.pickdate").datepicker({maxDate:"+0D",dateFormat: 'yy-mm-dd', changeYear: true, showAnim:'slideDown'});
               $("#submitdailyreport, #setgraph0_params, #setgraph1_params,#setgraph2_params,#setgraph5_params,#addillness,.adddailyillness").button();
               $("#setgraph7_params,#ihavechecked,#submitthisweeklyform,#setgraph8_params,#addinjbtn,#addinjtolist,.btn,#setgraph20_params,#setgraph30_params").button();
               $("#customexform").hide()
               $("#ihavechecked").click(function(){
                    $(this).slideUp("fast",function(){
                                    $("#submitthisweeklyform").fadeIn("fast");
                    })
               });
               $(".tickall").live("click",function(){
	$(this).parent().parent().find(".markdone2").each(function(index){
	          $(this).click();
	})


               });
               $(".dateset").change(function(){
	     $(".shownondateset").slideDown("fast");
               });
               $(".shownondateset").hide();
               $(".closepast").live("click",function(){
	     $("#pastplan").slideUp("fast");
               });
               $(".planhistbutton").click(function(){
	     var id = $(this).val();
	     var name = $(this).html();
	$.ajax({
	   type:"GET",
	   url:"http://otd.naturallyartificial.net/print/printplan2/showcustom/1/planid/"+id+"/userid/"+$("#userid").val(),
	   success:function(result){
	             $("#pastplan").hide();
	             $("#pastplan").html('<button class="closepast" style="background-color:#ab967b;border:0px;float:right;"><img src="/img/cross-script.png" alt=""/>Close this</button><h2>Past plan: '+name+'</h2><br/>'+result+'');
	             $("#pastplan").show("fast");
	             $(".hide").hide();
	   }
	});
               });
               $("#submitcustomexercise").click(function(){
                              var date = $("#date").val();
                              var exerciseid = $("#exerciseid :selected").val();
                              var weight = $("#weight").val();
                              var reps = $("#reps").val();
                              var sets = $("#sets").val();
	          var exerciseid2 = $("#exerciseid_com :selected").val();
	          var reps2 = $("#reps_com").val();
	          var exerciseid3 = $("#exerciseid_com2 :selected").val();
	          var reps3 = $("#reps_com2").val();
	          var comment = $("#additionalcomment").val();
	          var am_or_pm = $("#am_or_pm").is(':checked');
	          var abf = $("#abf:checked").val();
	          
                              if(!date || !exerciseid || !weight || !reps || !sets){
                                             alert("Please make sure that all text fields are filled in");
                              }
	          else if(parseInt(weight) > 300){
		alert("Please use sensible weight. Value should not exceed 300");
	          }
                              else{
		//alert(am_or_pm);
                                             var sdata = "date="+date+"&exerciseid="+exerciseid+"&weight="+weight+"&reps="+reps+"&sets="+sets;
		     if(am_or_pm == true){sdata = sdata + "&am_or_pm=1"}
                                             if(exerciseid2 && reps2){sdata = sdata+"&exerciseid_com="+exerciseid2+"&reps_com="+reps2;}
		     if(exerciseid2 && reps2 && exerciseid3 && reps3){sdata = sdata+"&exerciseid_com2="+exerciseid3+"&reps_com2="+reps3;}
		     if(comment){sdata = sdata+"&additionalcomment="+comment}
		     if(abf == 1){
		               sdata = sdata + "&abf=1";
		     }
		     else{
		               sdata = sdata + "&abf=0";
		     }
		     $.ajax({
                                                 type:"POST",
                                                 url:"/athlete/addcustomexercise/",
                                                 data:sdata,
                                                 success:function(result){
                                                                if(result=="success"){
                                                                               alert("Custom exercise added. ");
			                   $(".refreshprintplan").click();
			                   $(".refreshprintplancustom").click();
                                                                               //$("#customresult").hide().html("Custom exercise added. ").slideDown(200).delay(4000).slideUp(100);
                                                                }
                                                                else{
                                                                               alert("An error occured...Please try again.");
                                                                              //$("#customresult").hide().html("An error occured...").slideDown(200).delay(4000).slideUp(100);
                                                                }
                                                 }
                                             });
                              }
               });
               $(".injenable").change(function(){
                           if($(this).attr("checked")){
                              $("."+$(this).attr("id")+"sev, ."+$(this).attr("id")+"dur, ."+$(this).attr("id")+"act").removeAttr("disabled");
                           }
                           else{
                                    $("."+$(this).attr("id")+"sev, ."+$(this).attr("id")+"dur, ."+$(this).attr("id")+"act").attr("disabled",true);
                           }
               });
               $(".graphbtn").button();
               $(".graphbtn").click(function(){
                   var graphidname = ($(this).attr("id")).replace("_btn","");
                   $("#"+graphidname).slideToggle();
               });
               $(".helpbtn").click(function(){
                    $(this).next().slideToggle();
               });
               $("#ep_type,#ep_body_part,#ep_tissue_affected,#ep_pain_rating,#ep_training_modified,#ep_other_info").hide();
               $("#inj_or_nig,#type,#tissue,#muscle_group,#radio,#training_modified,").change(function(){
                   $(this).parent("div").next("div").slideDown("fast");
               });
               $("#addinjtolist").click(function(){
                              if($("#muscle_group").val() && $("#inj_or_nig :selected").val() != "" && $("#type :selected").val() != "" && $(" #tissue :selected").val() != "" && $(" #training_modified :selected").val() != "" && $("#radio :checked").val()){
                                   if($("#injlist").html() == "None"){
                                                  $("#injlist").html("");
                                   }
                                   var stringyid = $("#inj_or_nig :selected").val() + '***' + $("#muscle_group").val() +'***'+ $("#radio :checked").val() + '***' + $("#tissue :selected").val()
                                   + '***' + $("#type :selected").val() +'***' + $("#training_modified :selected").val();
                                   var stringy = '<div class="injblock rounded3" id="'+stringyid+'">['+$("#inj_or_nig :selected").val()+']'+$("#muscle_group").val()+' - Pain rating: '+$("#radio :checked").val()
                                                  +', '+$("#tissue :selected").text()+' - '+$("#type :selected").text()+' -- Training modified: '+$("#training_modified :selected").text()+
                                                  '<a class="injrem"> (x)</a><br/>&raquo;<span class="injdetail rounded3">'+$("#other_info").val()+'</span></div>';
                                   $("#injlist").append(stringy);
                                   $("#"+stringyid).hide().fadeIn("fast");
                                   $("#ep_type,#ep_body_part,#ep_tissue_affected,#ep_pain_rating,#ep_training_modified,#ep_other_info").slideUp("slow",function(){
                                                   $("#inj_or_nig,#type,#muscle_group,#tissue,#pain_rating,#training_modified,#other_info").val("");
                                   });
                                  

                              }
                              else{
                                             alert("Error! Please make sure you have filled in the form properly.");
                              }
               });
               $(".injrem").live("click", function(){

                                             $(this).parent().fadeOut("fast",function(){
                                                                                                                        $(this).remove();
                                                                     
                                             }
                                             )
               });
              
               $("#submitinjform").click(function(){
                              var data = "";
                              $(".injblock").each(function(){
                                            var stringy = $(this).attr("id") + "***" + $(this).children(".injdetail").html();
                                            data =  data + "[" +escape(stringy) + "]";
                              });
                              var date = $("#date").val();
                              $.ajax({
                                   type: "POST",
                                   url: "/athlete/saveinjuries",
                                   data: "date="+date+"&data="+data,
                                   success:function(result){
		     if(result == "success"){
                                                  window.location = "/Message/";
		     }
		     else{
		               alert(result);
		     }
                                   }
                              });

               });
               $(".musimg").click(function(){
                              $("#muscle_group").val($(this).attr("name"));
                              $("#ep_tissue_affected").slideDown("fast");
               });
               $(".dailyreportdate").change(function(){
	     var date = $(this).val();
	$.ajax({
	   type:"POST",
	   data:"date="+date,
	   url:"/athlete/getreportforedit",
	   success:function(result){
	             if(result != "false"){
	             var str= result.replace("{","");
	           str = str.replace("}","");
	           var splstr = str.split(",");
	           var tmp;
	           for(var ii = 0; ii<splstr.length;ii++){
		tmp = splstr[ii];
		tmp = tmp.replace(/"/,'');
		tmp = tmp.split(":");
		
		if(tmp[1]!=0 && tmp[1]!='0'){
		$("#"+tmp[0]).val(tmp[1]);
		//alert(tmp[1]);
		}
	           }
	             }
	             
	           
	           
	   }

	});
               });
               $("#DISABLEDbw_morning, #DISABLREDbw_evening").change(function(){
                              var idname = $(this).attr("id");
                              var thisval =Math.round(parseFloat( $(this).val())*10) / 10;
                              var prevval  = Math.round(parseFloat( $(this).prev().val())*10) / 10;
                              var nextcal = Math.round(parseFloat( $(this).next().val())*10) / 10;
                              //check if between 2 values
                              if(thisval > 0  && prevval > 0){
                                             if(thisval < prevval  ||  thisval > nextcal){
                                                            alert("Your body weight cannot be +/- 5kg. from previous day!");
                                                            $(this).val(prevval +5).focus().select();
                                             }
                              }
               }).focus(function(){$(this).select();});
               if(parseFloat($("#bw_morning, #bw_evening").prev().val()) < 0){
                              $(".downbtn, .upbtn").hide();
               }
               $(".downbtn").click(function(){
                    var current = Math.round(parseFloat($(this).next().next().val()) * 10) / 10;
                    var newval = current - 0.1;
                    newval = Math.round(newval*10)  / 10;
                    if(newval < ($(this).next().val())){
                                   alert("*Your body weight cannot be +/- 5kg. from previous day!");
                    }
                    else{
                                   $(this).next().next().val(newval);
                    }
               });
               
               $(".upbtn").click(function(){
                    var current = Math.round(parseFloat($(this).prev().prev().val()) * 10) / 10;
                    var newval = current + 0.1;
                    newval = Math.round(newval*10)  / 10;

                    if(newval > ($(this).prev().val())){
                                   alert("Your body weight cannot be +/- 5kg. from previous day!");
                    }
                    else{
                                   $(this).prev().prev().val(newval);
                    }
               });

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
               /*$("#setgraph1_params").click(function(){
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
               });*/

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
                                             imgsrc += "/sdate/"+sdate+"/edate/"+edate;
                              }

                              $("#chart5").attr('src',imgsrc);
               });
              /* $("#setgraph6_params").click(function(){
                              var uid = $("#userid").val();
                              var limit = $("#graph6_limit").val();
                              var imgsrc = "/chart/index/mode/drawchart_monotony/userid/"+uid;

                              if(limit){
                                             imgsrc += "/limit/"+limit;
                              }
                              else{
                                             imgsrc += "/limit/10";
                              }
                              $("input:checked").each(function(){
                                             imgsrc += "/"+$(this).attr("id") +"/1";
                              });

                              $("#chart6").attr('src',imgsrc);
               });*/
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
               $("#submitdailyreport").click(function(){
                              document.forms["daform"].submit();
               });
	$("#date").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, showAnim:'slideDown'});
                    $(".slider").slider({
                                   value: 1,
                                   min:1,
                                   max:5,
                                   step:0.5,
                                   slide: function(event, ui) {
                                                 $('#' +  ($(this).attr("id")).replace('slider','')).val(ui.value);
                                   }
                                   });
	 $(".sliderfluid").slider({
                                   value: 1,
                                   min:1,
                                   max:8,
                                   step:0.5,
                                   slide: function(event, ui) {
                                                 $("#fluid").val(ui.value);
                                   }
                                   });
                    $(".slider16").slider({
                                   value: 1,
                                   min:1,
                                   max:16,
                                   step:0.5,
                                   slide: function(event, ui) {
                                                 $('#' +  ($(this).attr("id")).replace('slider','')).val(ui.value);
                                   }
                                   });
                         $(".slider10").slider({
                                   value: 1,
                                   min:1,
                                   max:10,
                                   step:1,
                                   slide: function(event, ui) {
                                                 $('#' +  ($(this).attr("id")).replace('slider','')).val(ui.value);
                                   }
                                   });
                          $("#radio").buttonset();

                          //pending deletion
                    $(".markskipped").click(function(){
                                   var exid =($(this).parent().attr("id")).replace("epid","");
                                        if(!exid){
                                                       exid =($(this).parent().parent().attr("id")).replace("epid","");
                                        }
                                        $(this).after('<div class="red" id="ndap-exid-'+exid+'">\n\
                                             Please specify how many reps/sets you have done<br/> \n\
                                             <img src="/img/cross.png" alt="" class="destroyparent floatright"/> \n\
                                             Weight(kg) &raquo; <input id="ndap-weight-'+exid+'" type="text" class="tiny" />\n\
                                             Reps &raquo; <input id="ndap-reps-'+exid+'" type="text" class="tiny" /> \n\
                                             <input disabled="disabled" id="ndap-sets-'+exid+'" type="text" class="tiny hide" />Sets\n\
                                             <button class="markndap">Submit these info.</button></div>');
                                   $(".hide").hide();
                    });
                    $(".markskipped2").live("click",function(){
                                   var exid =($(this).parent().attr("class")).replace("epid","");
                                   var hascom = "";
	               var hascom2 = "";
                                   if($(".repscomfor"+exid).size()){
                                                 hascom = '(comb. ex.) Reps  &raquo; <input id="ndap-comreps-'+exid+'" type="text" class="tiny" />\n\ ';
                                   }
	               if($(".repscom2for"+exid).size()){
		     hascom2 = '(second comb. ex.) Reps  &raquo; <input id="ndap-com2reps-'+exid+'" type="text" class="tiny" />\n\ ';
	               }
                                        if(!exid){
                                                       exid =($(this).parent().parent().attr("class")).replace("epid","");
                                        }
                                        $(this).after('<div class="red" style="width:200px;position:relative;float:left;z-index:90;" id="ndap-exid-'+exid+'">\n\
                                             Please specify how many reps/sets you have done<br/> \n\
                                             <img src="/img/cross.png" alt="" class="destroyparent floatright"/> \n\
                                             Weight(kg) &raquo; <input id="ndap-weight-'+exid+'" type="text" class="tiny" />\n\
                                             Reps &raquo; <input id="ndap-reps-'+exid+'" type="text" class="tiny" /> \n\  '+hascom+hascom2+'<input disabled="disabled" id="ndap-sets-'+exid+'" type="text" class="tiny hide" value="1" />\n\
                                             <button class="markndap">Submit these info.</button></div>');
                                   $("#ndap-sets-"+exid).hide();
                    });
	$(".markfailed").live("click",function(){
	          var exerciseplanid = ($(this).parent().attr("class")).replace("epid","");
	          var repscom = "";
	          var repscom2 = "";
	          if($(".repscomfor"+exerciseplanid).html()){
		repscom = "&repscom=0";
	          }
	          if($(".repscom2for"+exerciseplanid).html()){
		repscom2 = "&repscom2=0";
	          }

	          var datastring = "actual_weight=0&mode=skipped&actual_total_lifted=0&exid="+exerciseplanid+"&actual_total_reps=0"+repscom+repscom2;
	          /*alert("System Testing: Implementation in progress\n\D.A.T.: "+datastring);*/
	          $.ajax({
			type: "POST",
			url: "/athlete/markexerciseplan",
			data: datastring,
			success: function(result){
				if(result == "success"){
					$(".stylefor"+exerciseplanid).removeClass("gridskipped");
					$(".stylefor"+exerciseplanid).removeClass("griddone");
					$(".stylefor"+exerciseplanid).addClass("gridfailed");
					
					$(".epid"+exerciseplanid+" .modtext").remove();
					$(".epid"+exerciseplanid).append("<span class=\"modtext\">Failed</span>");
                                                                                                    //$(".refreshprintplan").click();

				}
                                                                                else{
                                                                                               alert(result);
                                                                                }
			}
		});
	});
                    $(".markndap").live('click',function(){
                                   var mode = "skipped";
                         var exid = ($(this).parent().attr("id")).replace("ndap-exid-","");
                         if(!exid){
                                                       exid =($(this).parent().parent().attr("id")).replace("epid","");
                                        }
                         var reps = $("#ndap-reps-"+exid).val();
	     var repscom = $("#ndap-comreps-"+exid).val();
	     var repscom2 = $("#ndap-com2reps-"+exid).val();
                         var sets = $("#ndap-sets-"+exid).val();
                         var weight = $("#ndap-weight-"+exid).val();
	     var addidata1 = "";
	     var addidata2 = "";
                         var hc = "";
	     var multiple = 0;
                         if($("#ndap-comsets-"+exid)){
                                        hc = "&actual_reps_com="+$("#ndap-comreps-"+exid).val();
                         }
                         if(!isNumberAndNotNull(reps)  || !isNumberAndNotNull(sets) || !isNumberAndNotNull(weight)){
                                        alert("Please only enter numerical value.");
                         }
                         else{
	               multiple = parseInt(reps);
	               var txtformod = "";
	               if(repscom){/*multiple = multiple + parseInt(repscom);*/addidata1 = "&repscom="+repscom;txtformod = txtformod+"/"+repscom;}
	               if(repscom2){/*multiple = multiple + parseInt(repscom2);*/addidata2 = "&repscom2="+repscom2;txtformod = txtformod+"/"+repscom2;}
                                       $.ajax({
			type: "POST",
			url: "/athlete/markexerciseplan",
			data: "actual_weight="+weight+"&mode=skipped&actual_total_lifted="+(multiple * parseInt(sets) * parseInt(weight))+"&exid="+exid+"&actual_total_reps="+ (multiple * parseInt(sets))+hc+addidata1+addidata2,
			success: function(result){
				if(result == "success"){
                                                                                                    $(".stylefor"+exid).removeClass("gridfailed");
					$(".stylefor"+exid).removeClass("griddone");
					$(".stylefor"+exid).addClass("gridskipped");
					$("#ndap-exid-"+exid).slideUp("fast").remove();
					$(".epid"+exid+" .modtext").remove();
					$(".epid"+exid).append("<span class=\"modtext\">Mod. "+weight+"/"+reps+txtformod+"</span>");
					/*
					$("#epid"+exid).fadeOut(300,function(){$("#epid"+exid).attr("class",mode).fadeIn("fast");});
                                                                                                    $("#ndap-exid-"+exid).slideUp("fast").remove();
                                                                                                    $("#"+exid+"-saved").html("[saved as "+weight+"kg, "+reps+" reps, "+sets+" sets]");
                                                                                                    $(".refreshprintplan").click();
					*/

				}
                                                                                else{
                                                                                               alert(result);
                                                                                }
			}
		});
                         }
                    });
                    $("#saveemailbtn").click(function(){
                             var dataa = "email="+$("#emailaddress").val();
                             $.ajax({
                                 type: "POST",
                                 url: "/athlete/saveemail",
                                 data: dataa,
                                 success: function(result){
                                                alert(result);
                                 }
                             });
               });
               $(".deletecustomexercise").live("click",function(){
	     var exerciseID = ($(this).parent().attr("class")).replace("epid","");
	     
	     $.ajax({
	        type:"POST",
	        data:"delid="+exerciseID,
	        url:"/athlete/deletecustomexercise",
	        success:function(result){
	                  if(result == "success"){
	                  alert("Exercise has been removed.");
	                  $(".refreshprintplan").click();
	                  $(".refreshprintplancustom").click();
	                  }
	                  else{
		        alert("An error occured..");
		        $(".refreshprintplan").click();
	                  }
	        }
	     });
               });
               //pending for deletion
	$(".markdone").live("click",function(){
		var mode = ($(this).attr("class")).replace("mark","");
		var exid =($(this).parent().attr("id")).replace("epid","");
                                        if(!exid){
                                                       exid =($(this).parent().parent().attr("id")).replace("epid","");
                                        }
                                        var weight = $("#weightfor"+exid).html();
                                        var sets = $("#setsfor"+exid).html();
                                        var reps = $("#repsfor"+exid).html();
                                       
		$.ajax({
			type: "POST",
			url: "/athlete/markexerciseplan",
			data: "actual_weight="+weight+"&mode="+mode+"&exid="+exid+"&actual_total_lifted="+(parseInt(reps) * parseInt(sets) * parseInt(weight))+"&actual_total_reps="+ (parseInt(reps) * parseInt(sets)),
			success: function(result){
				if(result == "success"){
					$("#epid"+exid).fadeOut(300,function(){$("#epid"+exid).attr("class",mode).fadeIn("fast");});
				}
                                                                                else{
                                                                                               alert(result);
                                                                                }
			}
		});
		
	});
                    $(".markdone2").live("click",function(){
		var mode = ($(this).attr("class")).replace("mark","");
                                        mode = mode.replace("2","");
		var exid =($(this).parent().attr("class")).replace("epid","");
                                        if(!exid){
                                                       exid =($(this).parent().parent().attr("class")).replace("epid","");
                                        }
                                        var weight = $(".weightfor"+exid).html();
                                        var sets = $(".setsfor"+exid).html();
                                        var reps = $(".repsfor"+exid).html();
		var repscom = $(".repscomfor"+exid).html();
		var repscom2 = $(".repscom2for"+exid).html();
		var multiple = parseInt(reps);
		var addidata1 = "";
		var addidata2 = "";
		if(repscom){multiple = multiple + parseInt(repscom);addidata1 = "&repscom="+repscom;}
		if(repscom2){multiple = multiple + parseInt(repscom2);addidata2 = "&repscom2="+repscom2}

		$.ajax({
			type: "POST",
			url: "/athlete/markexerciseplan",
			data: "actual_weight="+weight+"&mode="+mode+"&exid="+exid+"&actual_total_lifted="+(multiple * parseInt(sets) * parseInt(weight))+"&actual_total_reps="+ (multiple * parseInt(sets))+addidata1+addidata2,
			success: function(result){
				if(result == "success"){
					//$("#epid"+exid).fadeOut(300,function(){$("#epid"+exid).attr("class",mode).fadeIn("fast");});
					
                                                                                                    $(".stylefor"+exid).removeClass("gridfailed");
					$(".stylefor"+exid).removeClass("gridskipped");
					$(".stylefor"+exid).addClass("griddone");
					$(".epid"+exid).children(".modtext").remove();
					//$(".refreshprintplan").click();
				}
                                                                                else{
                                                                                               alert(result);
                                                                                }
			}
		});

	});
                    $("#addillness").click(function(){
                                   if($.trim($("#addedillness").html()) == "No Illness"){                                                
                                                  $("#addedillness").html("");
                                   }
                              if($("#illnesslist :selected").text()){


                              //only if success
                              var id = $("#illnesslist :selected").val();
                              var sev = $("#severitylist").val();
                              var duration = $("#durationlist").val();
                              var activity = $("#activitylevellist").val();
                                             if( ($("#addedillness").text()).search($("#illnesslist :selected").text()) == -1){
                                   $("#addedillness").append( '<span id="'+id+ 'x'+sev+'x'+duration+ 'x'+ activity +'" class="tehillness"> - ' + $("#illnesslist :selected").text() + " : " + $("#severitylist :selected").text() + " ("
                                             +$("#durationlist :selected").text()  +") - <span class=\"underline\">" + $("#activitylevellist :selected").text() + " activity</span><a class=\"remfrmlst\">Remove from list</a><br/></span>");
                              //$("#illnesslist :selected").attr('disabled','disabled').next("option").attr('selected','selected');
                              $("#"+id).hide().fadeIn("fast");
                                             }
                              }
                    });
                    $(".remfrmlst").live('click', function(){$(this).parent().slideUp("fast",function(){$(this).remove()})});
                    $("#predicted_snatch, #predicted_cj, #bw").change(function(){
                                   if(!isNumber($(this).val())){
                                                  alert("Please enter numeric number");
                                                  $(this).val("");
                                   }

                    });
                   
                    $(".refreshprintplan").click(function(){
                              $.ajax({
                                             type: "GET",
                                             url: "/print/printplan2/showcustom/1/planid/"+$("#planid").val() + "/userid/"+$("#userid").val(),
                                             success: function(result){
                                                            //var res = result.split("<!-- break -->");
                                                            var ress;
                                                            if(result.search(/error/) > -1){
                                                                ress = "No plan to display...";
                                                            }
                                                            else{
                                                                ress = result;
                                                            }
                                                           $("#plangrid").html(ress);
                                                            //$("#printplanframe").html(res[1]+res[3]+res[5]);
                                                            //$("table .commentbox").hide();
                                                            $("*[title]").tipTip({defaultPosition:"top", delay:150, maxWidth:"300px", fadeIn:450 });
                                                            $("#plangrid .hide,#plangrid .deleteexplan2").hide();
                                             }
                              });


               });

               $(".refreshprintplancustom").click(function(){
                              $.ajax({
                                             type: "GET",
                                             url: "/print/printplan2/planid/"+$("#planid").val() + "/custom/1/hascss/1/showcustom/1",
                                             success: function(result){
                                                            //var res = result.split("<!-- break -->");
                                                            var ress;
                                                            if(result.search(/error/) > -1 || result.search(/setsfor/) == -1){
                                                                ress = "No custom exercises";
                                                            }
                                                            else{
                                                                ress = result;
                                                            }
                                                           $("#plangridcustom").html('                   <br/>\n\
                   <h2>Custom exercises in the past 7 days</h2>\n\
                   <br/>'+ress);
                                                            //$("#printplanframe").html(res[1]+res[3]+res[5]);
                                                            //$("table .commentbox").hide();
                                                            $("*[title]").tipTip({defaultPosition:"top", delay:150, maxWidth:"300px", fadeIn:450 });
                                                            $("#plangridcustom .hide").hide();
			$("#plangridcustom .deleteexplan2").hide();
                                             }
                              });


               });
                    $("#submitthisweeklyform").click(function(){

                                   var psnatch = $("#predicted_snatch").val();
                                   var pcj = $("#predicted_cj").val();
                                   var bw = $("#bw").val();
                                   if(psnatch && pcj && bw){
                                   var thisweek = $("#thisweekstartdate").html();
                                   var lastweek = $("#lastweekstartdate").html();
                                   var ill = ""; //will contain the illness data
                                   
                                   $("input:checked").each(function(){
                                                  ill = ill+ $(this).attr("id") +"x"+ $("."+$(this).attr("id")+"sev :selected").val() + "x"
                                                                 +$("."+$(this).attr("id")+"dur :selected").val() + "x" +
                                                                 $("."+$(this).attr("id")+"act :selected").val() + ",";
                                                 
                                   });
                                   ill = ill.slice(0,-1);

                                   var data = 'thisweek='+thisweek+'&lastweek='+lastweek+'&snatch='+psnatch+'&pcj='+pcj+'&bw='+bw+'&ill='+ill;
                                  // alert (data);
                                   
                                   $.ajax({
                                                  type: "POST",
                                                  url: "/athlete/addweeklyform",
                                                  data: data,
                                                  success:function(result){
                                                                 if(result == "success"){
                                                                                $(location).attr('href','/message');
                                                                 }
                                                                 else{
                                                                                alert(result);
                                                                 }
                                                  }
                                   });
                                   
                                   }
                                   else{
                                                  alert("Please make sure that you have entered your predicted max. snatch and C&J, and current body weight. ");
                                   }

                    });
                     $(".refreshprintplan").click();
	 $(".refreshprintplancustom").click();
	 $(".adddailyillness").click(function(){
	     var date = $(".pickdate").val();
	     var type = $(".type").val();
	     var sev = $(".sev").val();
	     var dur = $(".dur").val();
	     var act = $(".act").val();
	     var comm = ($("#illnesscomment").val()).replace("&","[AMP]");

	     //alert(date + " - " + type+ " - " +sev+ " - " +dur+ " - " +act);
	     $.ajax({
	         type:"POST",
	         url:"/athlete/adddailyillness/",
	         data:"date="+date+"&type="+type+"&sev="+sev+"&dur="+dur+"&act="+act+"&com="+comm,
	         success:function(result){
	                   $("#inneralertmessage").html(result);
                                                                $("#alertmessage").slideDown(200);
                                                                $("#alertmessage").delay(4000).slideUp("slow");
	         }
	     });
	 });
	 var first = 0;
	var speed = 700;
	var pause = 5000;
	function removeFirst(){
	first = $('ul#listticker li:first').html();
	
	$('ul#listticker li:first')
	.animate({opacity: 0}, speed)
	.fadeOut('slow', function() {$(this).remove();});
	addLast(first);
	}
	function addLast(first){
	last = '<li style="height:60px;">'+first+'</li>';
	$('ul#listticker').append(last)
	$('ul#listticker li:last')
	.animate({opacity: 1}, speed)
	.fadeIn('slow')
	}
	 /*interval = setInterval(removeFirst, pause);*/

	
});

function isNumberAndNotNull(value){ //value should be in string format
               var intval = parseInt(value);
               if(intval >= 0 && intval < 200){
                              return true;
               }
               else{
                              return false;
               }
}

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


