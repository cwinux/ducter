<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-resource-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput(['maxlength' => 128, 'value' => $model->getAppName($model['app_id']), 'readonly' => true])->label('产品名称') ?>

    <?= $form->field($model, 'svr_id')->dropDownlist($ser_array)->label('服务名称') ?>

    <?= $form->field($model, 'svr_pool_id')->dropDownlist($ser_pool_array)->label('服务池') ?>

    <?= $form->field($model, 'res_type')->dropDownlist(array('MySQL'=>'MySQL','Cbase'=>'Cbase','DNS'=>'DNS','Mq'=>'Mq','LVS'=>'LVS','SLB'=>'SLB','Mongo'=>'Mongo','Mcluster'=>'Mcluster','Gluster'=>'Gluster','Oracle'=>'Oracle'))->label('资源类型') ?>

    <?= $form->field($model, 'res_name',['inputOptions'=>['placeholder'=>'点击选择资源','class'=>'form-control','onclick'=>'addFault()','style'=>'font-size:12px']])->label('资源名称') ?>

   <?= $form->field($model, 'res_id')->textInput(["style"=>"display:none"])->label('唯一行号' ,["style"=>"display:none"]) ?>

    <?= $form->field($model, 'is_own')->dropDownlist(array(0=>"否", 1=>"是"))->label('是否为拥有者') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h5 class="modal-title" value="">选择资源</h5>
                </div>
     <div class="modal-body" id="addItems">
	<div style="width:90%; margin:0 auto;">
           <div style="height:350px;margin:20px;margin-left:100px">
            <!-- option必须带有 value 的值 -->
            <select id="magicsuggest" data-edit-select="1">
            </select>
          </div>
        </div>
        <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" onclick="subInfo()">提交</button>
        </div>
      </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>
<script src="./jquery-2.1.1.min.js"></script>
<script>
  function subInfo() {
    var input_val = document.getElementById('m-input').value;
    document.getElementById('dcmdappres-res_name').value = $("#m-input").attr('data-value');
    document.getElementById('dcmdappres-res_id').value = $("#m-input").attr('value');
    $('#addModal').modal('hide');
  }

  function addFault() {
    addItems();
    $('#addModal').modal('show');
    
  }
  function addItems() {
    $('#m-list li').remove();
    url = "/index.php?r=dcmd-resource/getitems";
    $.ajaxSetup({  
      async : false  
    }); 
    var cl = document.getElementById("dcmdappres-res_type").value;
    $.get(url,{class:cl},function(data){
      data = $.parseJSON(data);
      magicsuggest.options.length=0;
      for (i=0;i<data.length;i++){
        item = '<li class="m-list-item" data-value="' + data[i]['res_id'] + '" style="display: list-item;">' + data[i]['res_name'] + '</li>';
//        $('#m-list').append('<li class="m-list-item" data-value="2" style="display: list-item;">didi</li>')
        $('#m-list').append(item);
      }
    });
//    addFault(); 
//      alert("he"); 

  }
</script>
<script type="text/javascript">
$.fn.filterSelect = (function(){
//    addItems();
    // 我就 很 纠结的，把样式内嵌在这里了，让你怎么改!!!!
    var isInit = false;
    function initCss(){
        isInit = true;
        var style = document.createElement("style");
        var csstext = '.m-input-select{display:inline-block;*display:inline;position:relative;-webkit-user-select:none;}\
                        \n.m-input-select ul, .m-input-select li{padding:0;margin:0;}\
                        \n.m-input-select .m-input{padding-right:22px;}\
                        \n.m-input-select .m-input-ico{position:absolute;right:0;top:0;width:22px;height:100%;background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAYAAABWdVznAAAATElEQVQoU2NkIBEwkqiegTwNcXFx/4m1CW4DMZoWLVrEiOIkfJpAikGuwPADNk0wxVg1gASRNSErxqkBpgldMV4NuEKNvHggNg5A6gBo4xYmyyXcLAAAAABJRU5ErkJggg==) no-repeat 50% 50%;}\
                        \n.m-input-select .m-list-wrapper{}\
                        \n.m-input-select .m-list{display:none;position:absolute;z-index:1;top:100%;left:0;right:0;max-width:100%;max-height:300px;overflow:auto;border-bottom:1px solid #ddd;}\
                        \n.m-input-select .m-list-item{cursor:default;padding:5px;margin-top:-1px;list-style:none;background:#fff;border:1px solid #ddd;border-bottom:none;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}\
                        \n.m-input-select .m-list-item:hover{background:#2D95FF;}\
                        \n.m-input-select .m-list-item-active{background:#2D95FF;}';
        style = $("<style>"+ csstext +"</style>")[0];
        var head = document.head || document.getElementsByTagName("head")[0];
        if(head.hasChildNodes()){
            head.insertBefore(style, head.firstChild);
        }else{
            head.appendChild(style);
        };

    };

    return function(){
        !isInit && initCss();

        var $body = $("body");
        this.each(function(i, v){
            var $sel = $(v), $div = $('<div class="m-input-select"></div>');
            var $input = $("<input type='text' class='m-input' id='m-input' style='width:300px'/>");
            // var $wrapper = $("<div class='m-list-wrapper'><ul class='m-list'></ul></div>");
            var $wrapper = $("<ul class='m-list' id='m-list'></ul>");
            $div = $sel.wrap($div).hide().addClass("m-select").parent();
            $div.append($input).append("<span class='m-input-ico'></span>").append($wrapper);

            // 遮罩层显示 + 隐藏
            var wrapper = {
                show: function(){
                    $wrapper.show();
                    this.$list = $wrapper.find(".m-list-item:visible");
                    this.setIndex(this.$list.filter(".m-list-item-active"));
                    this.setActive(this.index);
                },
                hide: function(){
                    $wrapper.hide();
                },
                next: function(){
                    return this.setActive(this.index + 1);
                },
                prev: function(){
                    return this.setActive(this.index - 1);
                },
                $list: $wrapper.find(".m-list-item"),
                index: 0,
                $cur: [],
                setActive: function(i){
                    // 找到第1个 li，并且赋值为 active
                    var $list = this.$list, size = $list.size();
                    if(size <= 0){
                        this.$cur = [];
                        return;
                    }
                    $list.filter(".m-list-item-active").removeClass("m-list-item-active");
                    if(i < 0){
                        i = 0;
                    }else if(i >= size){
                        i = size - 1;
                    }
                    this.index = i;
                    this.$cur = $list.eq(i).addClass("m-list-item-active");
                    this.fixScroll(this.$cur);
                    return this.$cur;
                },
                fixScroll: function($elem){
                    // console.log($wrapper);
                    var height = $wrapper.height(), top = $elem.position().top, eHeight = $elem.outerHeight();
                    var scroll = $wrapper.scrollTop();
                    // 因为 li 的 实际　top，应该要加上 滚上 的距离
                    top += scroll;
                    if(scroll > top){
                        $wrapper.scrollTop(top);
                    }else if(top + eHeight > scroll + height){
                        // $wrapper.scrollTop(top + height - eHeight);
                        $wrapper.scrollTop(top + eHeight - height);
                    }
                },
                setIndex: function($li){
                    if($li.size() > 0){
                        this.index = this.$list.index($li);
                        $li.addClass("m-list-item-active").siblings().removeClass("m-list-item-active");
                    }else{
                        this.index = 0;
                    }
                }
            };

            // input 的操作
            var operation = {
                // 文字更变了，更新 li, 最低效率的一种
                textChange: function(){
                    val = $.trim($input.val());
                    $wrapper.find(".m-list-item").each(function(i, v){
                        if(v.innerHTML.indexOf(val) >= 0){
                            $(v).show();
                        }else{
                            $(v).hide();
                        }
                    });
                    wrapper.show();
                },
                // 设值
                setValue: function($li){
                    if($li && $li.size() > 0){
                        var val = $.trim($li.html());
                        $input.val(val).attr("value", $li.attr("data-value"));
                        $input.val(val).attr("data-value", val);
                        wrapper.setIndex($li);
                        $sel.val($li.attr("data-value")).trigger("change");
                    }else{
                        $input.val(function(i, v){
                            return $input.attr("placeholder");
                        });
                    };
                    wrapper.hide();
                    this.offBody();
                },
                onBody: function(){
                    var self = this;
                    setTimeout(function(){
                        self.offBody();
                        $body.on("click", self.bodyClick);
                    }, 10);
                },
                offBody: function(){
                    $body.off("click", this.bodyClick);
                },
                bodyClick: function(e){
                    var target = e.target;
                    if(target != $input[0] && target != $wrapper[0]){
                        wrapper.hide();
                        operation.setValue();
                        operation.offBody();
                    }
                }
            };

            // 遍历 $sel 对象
            function resetOption(){
                var html = "", val = "";
                $sel.find("option").each(function(i, v){
                    if(v.selected && !val){
                        val = v.text;
                    };
                    html += '<li class="m-list-item'+ (v.selected ? " m-list-item-active" : "") +'" data-value="'+ v.value +'">'+ v.text +'</li>';
                });
                $input.val(val);
                $wrapper.html(html);
            };
            $sel.on("optionChange", resetOption).trigger("optionChange");
            $sel.on("setEditSelectValue", function(e, val){
                // console.log(val);
                var $all = $wrapper.find(".m-list-item"), $item;
                for(var i = 0, max = $all.size(); i < max; i++){
                    $item = $all.eq(i);
                    if($item.attr("data-value") == val){
                        operation.setValue($item);
                        return;
                    }
                }
            });

            // input 聚焦
            $input.on("focus", function(){
                this.value = "";
                operation.textChange();
                operation.onBody();
            }).on("input propertychange", function(e){
                operation.textChange();
            }).on("keydown", function(e){
                // 上 38, 下 40， enter 13
                switch(e.keyCode){
                    case 38:
                        wrapper.prev();
                        break;
                    case 40:
                        wrapper.next();
                        break;
                    case 13:
                        operation.setValue(wrapper.$cur);
                        break;
                }
            });

            $div.on("click", ".m-input-ico", function(){
                // 触发 focus 和 blur 事件
                // focus 是因为 input 有绑定
                // 而 blur，实际只是失去焦点而已，真正隐藏 wrapper 的是 $body 事件
                $wrapper.is(":visible") ? $input.blur() : ($input.val("").trigger("focus"));
            });

            // 选中
            $wrapper.on("click", ".m-list-item", function(){
                operation.setValue($(this));
                return false;
            });

            setTimeout(function(){
                // for ie
                wrapper.hide();
            }, 1)


        });

        return this;
    };
})();
</script>
<script>
// 使用了这个插件，select该怎么用就怎么用
// 任何选择，同样会触发 select 的 更变的说【即select的值会同步更新】
//
var $select = $("select[data-edit-select]").filterSelect();
// --> 这时候的  $select === $("#magicsuggest");
// 也可以 用 $("#magicsuggest").on("change")，两者等价
$select.on("change", function(){
   addItems();
});
// 也可以通过 $("#magicsuggest").val() 拿到最新的值
// 通过 $("#magicsuggest").trigger("setEditSelectValue", 2); 设置选中的值为 2
// 通过 $("#magicsuggest").trigger("optionChange") 触发 更新 option 的值
</script> 
