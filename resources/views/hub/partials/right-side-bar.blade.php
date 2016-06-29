<div class="side-bar right-bar nicescroll">
    <a class='btn btn-default btn-xs pull-left waves-effect waves-light quick-control-plus-btn' data-toggle="modal" data-target="#quick-modal">
      <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
    <h4 class="text-center" style="margin-bottom: 2.5px; padding-right: 20px;">@trans('hub/hub.quickcontrol')</h4>
    <a href="javascript:toggleQuickRmBtn()" class='btn btn-inverse btn-xs pull-right waves-effect waves-light quick-control-btn'>
      <i class="fa fa-cog" aria-hidden="true"></i>
    </a>
    <div class="contact-list nicescroll" style="position: absolute">
        <ul class="list-group contacts-list quick-control">
            @if (count($quicks) > 0)
            @foreach ($quicks as $quick)
            <li class="list-group-item" id="quick{{ $quick['id'] }}">
                <a >
                    <a class='btn btn-danger btn-xs pull-left waves-effect waves-light quick-control-delete-btn' href="javascript:removeQuick({{ $quick['id'] }})" style="display: none">
                      <em class="fa fa-trash" aria-hidden="true"></em>
                    </a>
                </a>
                <div class="avatar">
                    <img src="@asset($quick['image'])">
                </div>
                <span class="name">{{ $quick['name'] }}</span>
                <div class="material-switch" style="position: absolute; right: 10px;">
                    <input id="quickbot{{ $quick['id'] }}" type="checkbox" @if ($quick->realStatus() == 1) checked @endif @if (in_array($quick->realStatus(),[-1,2])) disabled @endif/>
                    <label for="quickbot{{ $quick['id'] }}" class="label-default"></label>
                </div>
                <span class="clearfix"></span>
            </li>
            @endforeach
            @endif
        </ul>
    </div>
</div>
<div id="quick-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content p-0 b-0">
            <div class="panel panel-color panel-custom">
                <div class="panel-heading">
                    <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="panel-title">@trans('hub/hub.add_quickcontrol')</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="input-group m-t-10">
                            <input type="bot" id="bot" name="quickbot" class="form-control" placeholder="Name or id">
                            <span class="input-group-btn">
                                <button type="submit" class="btn waves-effect waves-light btn-default" onclick="addQuick()">Add</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>>
