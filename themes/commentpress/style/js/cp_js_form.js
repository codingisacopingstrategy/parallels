addComment={moveForm:function(m,l,a,i,c){this.disableForm();var b;var h=this.I(m);var n=this.I(a);var g=this.I("cancel-comment-reply-link");var k=this.I("comment_parent");var d=this.I("comment_post_ID");if(this.I("text_signature")){var f=this.I("text_signature")}else{var f=""}if(!h||!n||!g||!k){this.enableForm();return}if(d&&i){d.value=i}k.value=l;if(f!==""){f.value=c}this.respondID=a;addComment.setTitle(l,c,"set");if(!this.I("wp-temp-form-div")){b=document.createElement("div");b.id="wp-temp-form-div";b.style.display="none";n.parentNode.insertBefore(b,n)}h.parentNode.insertBefore(n,h.nextSibling);if(cp_special_page!="1"&&cp_promote_reading=="0"&&l=="0"){g.style.display="none"}else{g.style.display=""}g.onclick=function(){return addComment.cancelForm()};if(cp_tinymce=="1"){this.enableForm()}else{try{this.I("comment").focus()}catch(j){}}n.style.display="block";addComment.clearCommentHighlight(this.parentID);addComment.highlightComment(l);this.text_signature=c;this.parentID=l;return false},moveFormToPara:function(d,b,a){var c="reply_to_para-"+d;addComment.moveForm(c,"0","respond",a,b);return false},cancelForm:function(){var e=addComment.I("wp-temp-form-div");var d=addComment.I(addComment.respondID);var h=this.I("cancel-comment-reply-link");if(!e||!d){return}addComment.clearCommentHighlight(this.parentID);if(cp_special_page!="1"){var f="";if(addComment.I("text_signature")){f=addComment.I("text_signature").value;addComment.I("text_signature").value="";var b=jQuery("#para_wrapper-"+f+" .reply_to_para").attr("id");var a=b.split("-")[1]}if(cp_promote_reading=="1"){if(d.style.display!="none"){d.style.display="none"}}else{var c=addComment.I("comment_post_ID").value;addComment.moveFormToPara(a,f,c);return false}}else{}addComment.disableForm();var g=addComment.I("comment_parent").value;addComment.I("comment_parent").value="0";e.parentNode.insertBefore(d,e);e.parentNode.removeChild(e);h.style.display="none";h.onclick=null;addComment.setTitle("0",f,"cancel");this.text_signature="";addComment.enableForm();return false},I:function(a){return document.getElementById(a)},enableForm:function(){if(cp_tinymce=="1"){setTimeout(function(){tinyMCE.execCommand("mceAddControl",false,"comment")},1)}},disableForm:function(){if(cp_tinymce=="1"){tinyMCE.execCommand("mceRemoveControl",false,"comment")}},setTitle:function(d,a,c){var b=addComment.I("respond_title");if(d===undefined||d=="0"){if(a===undefined||a==""){if(cp_special_page=="1"){b.innerHTML="Leave a comment"}else{b.innerHTML=jQuery("#para_wrapper-"+a+" a.reply_to_para").text();var e=jQuery("#para_wrapper-"+addComment.text_signature+" .commentlist");if(e[0]&&cp_promote_reading=="0"){jQuery("#para_wrapper-"+addComment.text_signature+" div.reply_to_para").show()}jQuery("#para_wrapper-"+a+" div.reply_to_para").hide()}}else{b.innerHTML=jQuery("#para_wrapper-"+a+" a.reply_to_para").text();var e=jQuery("#para_wrapper-"+addComment.text_signature+" .commentlist");if((e[0]&&cp_promote_reading=="0")||cp_promote_reading=="1"){if(addComment.text_signature!==undefined){jQuery("#para_wrapper-"+addComment.text_signature+" div.reply_to_para").show()}}if(cp_promote_reading=="0"){jQuery("#para_wrapper-"+a+" div.reply_to_para").hide()}else{if(c=="cancel"){jQuery("#para_wrapper-"+a+" div.reply_to_para").show()}else{jQuery("#para_wrapper-"+a+" div.reply_to_para").toggle()}}}}else{b.innerHTML=jQuery("#comment-"+d+" > .reply").text();if(a===undefined||a==""){a==""}if(cp_promote_reading=="1"){if(addComment.text_signature!==undefined){jQuery("#para_wrapper-"+addComment.text_signature+" div.reply_to_para").show()}jQuery("#para_wrapper-"+a+" div.reply_to_para").show()}}},highlightComment:function(a){if(a!="0"){jQuery("#comment-"+a+" > .reply").css("display","none")}jQuery("#li-comment-"+a+" > .comment-wrapper").css("background-color","#CBFFBD");addComment.commentBorder=jQuery("#comment-"+a+" > .comment-content").css("border-bottom");jQuery("#comment-"+a+" > .comment-content").css("border-bottom","1px dashed #b8b8b8")},clearCommentHighlight:function(a){if(a!="0"){jQuery("#comment-"+a+" > .reply").css("display","block")}jQuery("#li-comment-"+a+" > .comment-wrapper").css("background-color","#fff");jQuery("#comment-"+a+" > .comment-content").css("border-bottom",addComment.commentBorder)},clearAllCommentHighlights:function(){jQuery(".reply").css("display","block");jQuery(".comment-wrapper").css("background-color","#fff");jQuery("#comment-"+parentID+" > .comment-content").css("border-bottom",addComment.commentBorder)},getTextSig:function(){return this.text_signature},getLevel:function(){if(this.parentID===undefined||this.parentID==="0"){return true}else{return false}}};