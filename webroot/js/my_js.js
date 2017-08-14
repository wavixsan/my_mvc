
function _xml(str,id,post){
    //document.getElementById(id).innerHTML='<div style="background:#fff;">Loading...</div>'
    if(post!==true){post=false;}
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    xmlhttp.onreadystatechange=function(){
        if(xmlhttp.readyState==4 && xmlhttp.status==200){
            document.getElementById(id).innerHTML=xmlhttp.responseText;
        }
    }
    if(post){
        xmlhttp.open("POST","?",true);///????
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xmlhttp.send(str);
    }else{
        xmlhttp.open("GET",str,true);
        xmlhttp.send();
    }
    console.log(str+' | '+id);
}

function _form(form){
    var text="",g=true,arr=[].slice.call(form);
    for(var i=0; i<arr.length; i++){
        switch(arr[i].type){
            case "checkbox": if(arr[i].checked!=true){break;}
            default:
                if(arr[i].name) {// && arr[i].value
                    if(g){text+="&";}else{text+="?"; g=true;}
                    text+=encodeURIComponent(arr[i].name)+"="+encodeURIComponent(arr[i].value);
                }
        }
    }
    return text;
}

function addCart(url)
{
    _xml(url,'cart');
}