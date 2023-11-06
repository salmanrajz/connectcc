<label for="localminutes" class="control-label col-md-12 col-sm-12 col-xs-12" >Manager Name</label>
<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
    <select name="manager" id="manager" class="form-control">
        @if ($location == 'Karachi')
            {{-- <option value="Taimoor Iqbal">Mr. Taimoor</option> --}}
            <option value="Rashid Qureshi">Mr. Rashid Qureshi</option>
        @else
            <option value="Khizar">Mr. Khizar</option>
            <option value="Majid">Mr. Majid</option>
            {{-- <option value="Shehroze Cheema">Mr. Shehroz</option> --}}
        @endif
    </select>
</div>
