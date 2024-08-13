(()=>{"use strict";var e,t={784:(e,t,n)=>{const l=window.wp.blocks,o=JSON.parse('{"u2":"meta-box/submission-form"}'),s=window.wp.element,a=window.wp.blockEditor,r=window.wp.components,i=window.wp.i18n,m=window.wp.serverSideRender;var b=n.n(m);(0,l.registerBlockType)(o.u2,{edit:function(e){let{attributes:t,setAttributes:n}=e;const{meta_box_id:l,ajax:o,allow_scroll:m,edit:u,allow_delete:c,force_delete:d,show_add_more:_,post_type:f,post_id:h,post_status:p,post_fields:w,label_title:E,label_content:C,label_excerpt:g,label_date:v,label_thumbnail:x,submit_button:P,add_button:T,delete_button:y,redirect:R,confirmation:k,delete_confirmation:O,recaptcha_key:D,recaptcha_secret:j}=t;let I=Object.keys(mbfsData.post_types).map((e=>({label:e,value:e}))),S=Object.keys(mbfsData.post_statuses).map((e=>({label:e,value:e})));return(0,s.createElement)("div",(0,a.useBlockProps)(),(0,s.createElement)(a.InspectorControls,null,(0,s.createElement)(r.PanelBody,{className:"mbfs-block",title:(0,i.__)("Settings","mb-frontend-submission")},(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("ID","mb-frontend-submission"),help:(0,i.__)("Field group ID(s). If multiple field groups, enter their IDs separated by commas. Optional.","mb-frontend-submission"),value:l,onChange:e=>n({meta_box_id:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.ToggleControl,{label:(0,i.__)("Enable ajax submission","mb-frontend-submission"),checked:o,onChange:()=>n({ajax:!o})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.ToggleControl,{label:(0,i.__)("Allow scroll","mb-frontend-submission"),help:(0,i.__)("Enable scroll to message after ajax submission.","mb-frontend-submission"),checked:m,onChange:()=>n({allow_scroll:!m})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.ToggleControl,{label:(0,i.__)("Edit","mb-frontend-submission"),help:(0,i.__)("Allow users to edit the post after submitting. If enabled, then ajax submission will be disabled.","mb-frontend-submission"),checked:u,onChange:()=>n({edit:!u})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.ToggleControl,{label:(0,i.__)("Allow delete","mb-frontend-submission"),help:(0,i.__)("Allow users to delete the submitted post.","mb-frontend-submission"),checked:c,onChange:()=>n({allow_delete:!c})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.ToggleControl,{label:(0,i.__)("Force delete","mb-frontend-submission"),help:(0,i.__)("Whether to delete the submitted post permanently or temporarily (move to Trash).","mb-frontend-submission"),checked:d,onChange:()=>n({force_delete:!d})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.ToggleControl,{label:(0,i.__)("Show add more","mb-frontend-submission"),help:(0,i.__)("Show add new button after submit.","mb-frontend-submission"),checked:_,onChange:()=>n({show_add_more:!_})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.SelectControl,{label:(0,i.__)("Post type","mb-frontend-submission"),help:(0,i.__)("The submitted post type. Default is the first post type defined in the meta box. If meta box is made for multiple post types, you should set this attribute to the correct one.","mb-frontend-submission"),value:f,options:[{label:(0,i.__)("-- Choose Post Type --","mb-frontend-submission"),value:""},...I],onChange:e=>n({post_type:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Post ID","mb-frontend-submission"),help:(0,i.__)('The post ID. Used when you want to update an existing post. If you want to pass the ID of the current post, set it to "current".',"mb-frontend-submission"),value:h,onChange:e=>n({post_id:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.SelectControl,{label:(0,i.__)("Post status","mb-frontend-submission"),help:(0,i.__)("The status for submitted posts.","mb-frontend-submission"),value:p,options:[{label:(0,i.__)("-- Choose Post Status --","mb-frontend-submission"),value:""},...S],onChange:e=>n({post_status:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.FormTokenField,{__experimentalAutoSelectFirstMatch:!0,__experimentalExpandOnFocus:!0,label:(0,i.__)("Post Fields","mb-frontend-submission"),help:(0,i.__)("List of post fields you want to show in the frontend.","mb-frontend-submission"),value:w,suggestions:["title","content","excerpt","date","thumbnail"],onChange:e=>n({post_fields:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Title field label","mb-frontend-submission"),value:E,onChange:e=>n({label_title:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Content field label","mb-frontend-submission"),value:C,onChange:e=>n({label_content:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Excerpt field label","mb-frontend-submission"),value:g,onChange:e=>n({label_excerpt:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Date field label","mb-frontend-submission"),value:v,onChange:e=>n({label_date:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Thumbnail field label","mb-frontend-submission"),value:x,onChange:e=>n({label_thumbnail:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Submit button text","mb-frontend-submission"),help:(0,i.__)("The submit button text.","mb-frontend-submission"),value:P,onChange:e=>n({submit_button:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Add new button text","mb-frontend-submission"),value:T,onChange:e=>n({add_button:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Delete button text","mb-frontend-submission"),value:y,onChange:e=>n({delete_button:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Custom redirect URL","mb-frontend-submission"),value:R,onChange:e=>n({redirect:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Confirmation text","mb-frontend-submission"),help:(0,i.__)("The text for the confirmation message when the form is successfully submitted.","mb-frontend-submission"),value:k,onChange:e=>n({confirmation:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Delete confirmation text","mb-frontend-submission"),help:(0,i.__)("The text for the confirmation message when the post is deleted.","mb-frontend-submission"),value:O,onChange:e=>n({delete_confirmation:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Recaptcha key","mb-frontend-submission"),help:(0,i.__)("Google reCaptcha site key (version 3).","mb-frontend-submission"),value:D,onChange:e=>n({recaptcha_key:e})})),(0,s.createElement)(r.PanelRow,null,(0,s.createElement)(r.TextControl,{label:(0,i.__)("Recaptcha secret","mb-frontend-submission"),help:(0,i.__)("Google reCaptcha secret key (version 3).","mb-frontend-submission"),value:j,onChange:e=>n({recaptcha_secret:e})})))),(0,s.createElement)(b(),{block:"meta-box/submission-form",attributes:t}))},save:function(){return null}})}},n={};function l(e){var o=n[e];if(void 0!==o)return o.exports;var s=n[e]={exports:{}};return t[e](s,s.exports,l),s.exports}l.m=t,e=[],l.O=(t,n,o,s)=>{if(!n){var a=1/0;for(b=0;b<e.length;b++){n=e[b][0],o=e[b][1],s=e[b][2];for(var r=!0,i=0;i<n.length;i++)(!1&s||a>=s)&&Object.keys(l.O).every((e=>l.O[e](n[i])))?n.splice(i--,1):(r=!1,s<a&&(a=s));if(r){e.splice(b--,1);var m=o();void 0!==m&&(t=m)}}return t}s=s||0;for(var b=e.length;b>0&&e[b-1][2]>s;b--)e[b]=e[b-1];e[b]=[n,o,s]},l.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return l.d(t,{a:t}),t},l.d=(e,t)=>{for(var n in t)l.o(t,n)&&!l.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},l.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={826:0,431:0};l.O.j=t=>0===e[t];var t=(t,n)=>{var o,s,a=n[0],r=n[1],i=n[2],m=0;if(a.some((t=>0!==e[t]))){for(o in r)l.o(r,o)&&(l.m[o]=r[o]);if(i)var b=i(l)}for(t&&t(n);m<a.length;m++)s=a[m],l.o(e,s)&&e[s]&&e[s][0](),e[s]=0;return l.O(b)},n=self.webpackChunk=self.webpackChunk||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var o=l.O(void 0,[431],(()=>l(784)));o=l.O(o)})();