/* jce - 2.8.17 | 2020-08-27 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2020 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){var each=tinymce.each,DOM=tinymce.DOM;tinymce.create("tinymce.plugins.AttributesPlugin",{init:function(ed,url){this.editor=ed,ed.addButton("attribs",{title:"attribs.desc",cmd:"mceAttributes"}),ed.onNodeChange.add(function(ed,cm,n,co){if(cm.setDisabled("attribs",n&&"BODY"==n.nodeName),n)do cm.setDisabled(n.nodeName.toLowerCase(),0),cm.setActive(n.nodeName.toLowerCase(),1);while(n=n.parentNode)}),ed.onPreInit.add(function(){ed.formatter.register({attributes:{inline:"span",remove:"all",onformat:function(elm,fmt,vars){each(vars,function(value,key){ed.dom.setAttrib(elm,key,value)})}}});var cm=ed.controlManager,form=cm.createForm("attributes_form"),boolAttrs=ed.schema.getBoolAttrs();ed.addCommand("mceAttributes",function(){ed.windowManager.open({title:ed.getLang("attributes.desc","Attributes"),size:"mce-modal-landscape-small",open:function(){var label=ed.getLang("insert","Insert"),node=ed.selection.getNode();if(node){var name=node.nodeName.toLowerCase(),elm=ed.schema.elements[name]||{};form.empty(),elm.attributes&&each(elm.attributes,function(val,key){var ctrl;return 0===key.indexOf("on")||(0===key.indexOf("item")||(ctrl=boolAttrs[key]?cm.createListBox(key+"_ctrl",{label:ed.getLang(key,key),name:key,items:[{title:"Yes",value:"1"},{title:"No",value:"0"}]}):cm.createTextBox(key+"_ctrl",{label:ed.getLang(key,key),name:key}),void form.add(ctrl)))}),form.renderTo(this.id+"_content")}DOM.setHTML(this.id+"_insert",label)},buttons:[{title:ed.getLang("common.cancel","Cancel"),id:"cancel"},{title:ed.getLang("insert","Insert"),id:"insert",onsubmit:function(e){form.submit();Event.cancel(e)},classes:"primary",scope:self}]})})})}}),tinymce.PluginManager.add("attributes",tinymce.plugins.AttributesPlugin)}();