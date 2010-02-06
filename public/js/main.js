$(document).ready(function(){
	$("#idDivCalendar").datepicker({dayNamesShort:['日','一','二','三','四','五','六'],
		                        monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
					nextText:'下一月',
					prevText:'上一月',
					dateFormat:'yy-mm-dd',
					onSelect:function(s,inst){
						window.location.href='http://ffrecorder.com/history.php?d=' + s;	
					},
					onChangeMonthYear:function(year,month,inst)
					{
						$.ajax({type :"GET",
							url : "http://ffrecorder.com/ajax.php?m=" + year + '-' + (month<10? '0' + month : month),
							success:function(r){
								var counts  = r.split(':');
								$("a.ui-state-default").each(function(){
									var count = counts[$(this).text()-1];
									var star = 0;
									if(count > 0 && count <= 20)
									{
										star = 1;
									}
									else if(count > 20 && count <=40)
									{
										star = 2;
									}
									else if(count > 40 && count <=80)
									{
										star = 3;
									}
									else if(count > 80)
									{
									 	star = 4;
									}	
									$(this).parent().attr('title',count);
									$(this).addClass('day-visit-star-' + star);
									$(this).css('backgroundImage','url(none)')
								});		
							}
						});
						
					}
	});
	
});
