webpackJsonp([1],{CFup:function(t,e){},GFjW:function(t,e,i){t.exports=i.p+"static/img/carol.85372ab.png"},Gqps:function(t,e){},NHnr:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=i("/5sW"),n=i("zL8q"),o=i.n(n),a=(i("tvR6"),{render:function(){var t=this.$createElement,e=this._self._c||t;return e("div",{attrs:{id:"app"}},[e("img",{staticClass:"full",attrs:{src:i("Pvhc")}}),this._v(" "),e("img",{staticClass:"presents",attrs:{src:i("pTTK")}}),this._v(" "),e("img",{staticClass:"carol",attrs:{src:i("GFjW")}}),this._v(" "),e("router-view")],1)},staticRenderFns:[]}),u=i("VU/8")({name:"app"},a,!1,function(t){i("CFup")},null,null).exports,r=i("/ocq"),c=i("mtWM"),p=i.n(c),l=i("y1vT"),d=i.n(l),f={name:"Question",data:function(){return{question:{},options:[],questions:[],values:[]}},computed:{questionsWithoutSelf:function(){var t=this;return this.questions.filter(function(e){return e.id!==t.question.id})}},watch:{$route:function(t,e){Number(e.params.question)!==Number(t.params.question)&&this.getQuestion()}},methods:{getQuestion:function(){var t=this,e="https://fishpi.paul.style/api/v1/question";this.$route.params.question&&(e+="/"+this.$route.params.question),p.a.get(e).then(function(e){t.question=e.data.question,t.options=e.data.options})},getQuestions:function(){var t=this;p.a.get("https://fishpi.paul.style/api/v1/questions").then(function(e){t.questions=e.data.map(function(t){return{label:"Story #"+t.id,id:t.id}})})},updateText:d()(function(){return p.a.post("https://fishpi.paul.style/api/v1/question/"+this.question.id,{text:this.question.text})},1e3),updateOptionText:d()(function(t){p.a.post("https://fishpi.paul.style/api/v1/question/"+this.question.id+"/option/"+t.id,{text:t.text})},500),updateToQuestion:function(t){p.a.post("https://fishpi.paul.style/api/v1/question/"+this.question.id+"/option/"+t.id,{to_question_id:t.to_question_id})},goTo:function(t){var e=this;t.to_question_id&&this.$router.push({name:"QuestionPage",params:{question:t.to_question_id}}),t.id&&p.a.post("https://fishpi.paul.style/api/v1/question",{text:"",option:t.id}).then(function(t){var i=t.data.question.id;e.addOption(i),e.addOption(i),setTimeout(function(){e.goTo({to_question_id:i})},1e3)})},addOption:function(t){p.a.post("https://fishpi.paul.style/api/v1/question/"+t+"/option",{text:""})}},created:function(){this.getQuestion(),this.getQuestions()}},h={render:function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"question"},[i("el-card",{staticClass:"box-card"},[i("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[i("span",[t._v("Story #"+t._s(t.question.id))])]),t._v(" "),i("el-input",{staticClass:"question-input",attrs:{type:"textarea",autosize:{minRows:3},placeholder:"Once upon a time..."},on:{change:t.updateText},model:{value:t.question.text,callback:function(e){t.$set(t.question,"text",e)},expression:"question.text"}})],1),t._v(" "),i("el-card",{staticClass:"box-card"},t._l(t.options,function(e,s){return i("div",{key:s,staticClass:"choice-row"},[i("el-input",{staticClass:"choice",attrs:{type:"text",maxlength:20},on:{change:function(i){t.updateOptionText(e)}},model:{value:e.text,callback:function(i){t.$set(e,"text",i)},expression:"option.text"}}),t._v(" "),i("el-select",{staticClass:"choice-select",attrs:{placeholder:"Select"},on:{change:function(i){t.updateToQuestion(e)}},model:{value:e.to_question_id,callback:function(i){t.$set(e,"to_question_id",i)},expression:"option.to_question_id"}},[i("el-option",{attrs:{label:"New",value:0}}),t._v(" "),t._l(t.questionsWithoutSelf,function(t){return i("el-option",{key:t.id,attrs:{label:t.label,value:t.id}})})],2),t._v(" "),i("el-button",{staticClass:"green-button",attrs:{type:"primary",icon:"el-icon-d-arrow-right"},on:{click:function(i){t.goTo(e)}}})],1)}))],1)},staticRenderFns:[]},q=i("VU/8")(f,h,!1,function(t){i("Gqps")},"data-v-5f073904",null).exports;s.default.use(r.a);var v=new r.a({routes:[{path:"/:question?",name:"QuestionPage",component:q}]});s.default.use(o.a),s.default.config.productionTip=!1,new s.default({el:"#app",router:v,render:function(t){return t(u)}})},Pvhc:function(t,e,i){t.exports=i.p+"static/img/christmas.89d2b3c.gif"},pTTK:function(t,e,i){t.exports=i.p+"static/img/coosto.8678395.png"},tvR6:function(t,e){}},["NHnr"]);
//# sourceMappingURL=app.c98ffed5b09f68eb31d0.js.map