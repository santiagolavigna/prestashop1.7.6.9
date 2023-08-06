{*<div class="container">*}
{*    <div class="row">*}
{*        <div class="d-flex align-items-center col-sm-7 col-5">*}
{*            <div class="col-12 flex-column pr-0 mt-0 mt-sm-2">*}
{*                <p class="mb-0 text-right text-sm-left"><strong></strong></p>*}
{*                <p class="mb-0 text-right text-sm-left" style="font-size: 10px;"></p>*}
{*            </div>*}
{*        </div>*}
{*        <div class="d-flex align-items-center col-sm-5 col-7 flex-row-reverse flex-sm-row">*}
{*            <div class="col-6 col-sm-7 flex-column pr-0 mt-0 mt-sm-2">*}
{*                <p class="mb-0 text-right"><strong></strong></p>*}
{*                <p class="mb-0 text-right"></p>*}
{*            </div>*}
{*            <img class="img-responsive col-6 col-sm-5 pl-0 pr-0 mb-0" src="{$weatherData.current.condition.icon}" alt="Weather Icon">*}
{*        </div>*}
{*    </div>*}
{*</div>*}

<div class="container">
    <div class="row">
        <div class="col-5 flex-column mt-3">
            <p class="mb-0" style="font-size: 12px;"><strong>{$weatherData.location.name}, {$weatherData.location.country}</strong></p>
            <p class="mb-0" style="font-size: 10px;">{$weatherData.location.localtime}</p>
        </div>
        <div class="col-7 d-flex">
            <div class="col-8 pr-0 pl-0 mt-3">
                <p class="mb-0 text-right" style="font-size: 12px;"><strong>{$weatherData.current.temp_c}(°C) </strong></p>
                <p class="mb-0 text-right" style="font-size: 12px;">{$weatherData.current.condition.text}</p>
            </div>
            <div class="col-4">
                <img class="img-responsive mt-1" src="//cdn.weatherapi.com/weather/64x64/day/116.png" alt="Weather Icon">
            </div>
        </div>
    </div>
</div>