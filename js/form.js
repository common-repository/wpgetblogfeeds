/**
 * @author: Arjun Jain ( http://www.arjunjain.info ) 
 * @license: GNU GENERAL PUBLIC LICENSE Version 3
 *
 */

function insertfeed(em,blogpath){
	var formname=em.name;
	var type=formname.split("_");
	var txtTitle="txtTitle_"+type[1];
	var txtContent="txtContent_"+type[1];
	var title=document.forms[formname].elements[txtTitle].value;
	var content=document.forms[formname].elements[txtContent].value;	
	jQuery.ajax({
		type:"POST",  
		url: blogpath,  
		data:"ptitle="+title+"&pcontent="+content,
		success: function(data){
			alert(data);
		}
	});
	return false;
}

function getfeeds(em,blogpath,imagepath){
	var urlid=document.forms[em.name].elements["feedid"].value;
	document.getElementById("formdata").innerHTML='<div style=" margin-top:20px;" ><img src="'+imagepath+'" /></div>';
	jQuery.ajax({
		type:"POST",  
		url: blogpath,  
		data:"feedid="+urlid,
		success: function(data){
			document.getElementById("formdata").innerHTML=data;
		}
	});
	return false;
}