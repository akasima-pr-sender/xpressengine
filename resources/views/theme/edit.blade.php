    @section('page_title')
        <h2>{{ xe_trans('xe::editTheme') }} - {{ $theme->getTitle() }}</h2>
    @stop

    @section('page_description')
        {{xe_trans('xe::editThemeDescription')}}
    @stop

    @if(count($files) === 0)
        <p>{{xe_trans('xe::themeNotSupportEdit')}}</p>
    @else
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked" style="margin-bottom: 10px;">
                @foreach($files as $name => $path)
                <li role="presentation" @if($name === $editFile['fileName'])class="active"@endif>
                    <a href="{{ route('settings.theme.edit', ['theme' => $theme->getId(), 'file' => $name]) }}">{{ $name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-10">
            <p>
                {{ $editFile['fileName'] }}
            </p>
            <form role="form" action="{{ route('settings.theme.edit') }}" method="post" id="_theme">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="theme" value="{{ $theme->getId() }}">
                <input type="hidden" name="file" value="{{ $editFile['fileName'] }}">

                <textarea class="form-control" rows="30" name="content" @if(request('view_origin') === 'Y') readonly="readonly" @endif>{{ old('content', $editFile['content']) }}</textarea>

                <div class="pull-right" style="margin-top:10px;">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#widgetModal">위젯 추가</button>
                    <button type="submit" class="btn btn-default">{{ xe_trans('xe::applyModified') }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- widget modal --}}
    <div class="modal fade" id="widgetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">위젯 추가</h4>
                </div>
                <div class="modal-body">
                    {{ uio('widget', ['id'=>'widgetGen', 'show_code'=>false]) }}
                </div>
                <div class="xe-modal-footer">
                    <button type="button" class="xe-btn xe-btn-secondary" data-dismiss="modal">취소</button>
                    <button type="button" class="xe-btn xe-btn-primary __xe_insert_widget" data-dismiss="modal">추가</button>
                </div>
            </div>
        </div>
    </div>

    @endif

    {!!
        XeFrontend::html('theme.insert-widget')->content("
        <script>
            $('.__xe_insert_widget').click(function(){
                $('#widgetGen').widgetGenerator().generate(function(data){

                    $('#widgetModal').modal('hide');

                    // add code to textarea
                    var txt = $('textarea[name=content]');
                    var caretPos = txt[0].selectionStart;
                    var textAreaTxt = txt.val();
                    txt.val(textAreaTxt.substring(0, caretPos) + data.code + textAreaTxt.substring(caretPos) );

                });
            })
        </script>
        ")->load();
    !!}
