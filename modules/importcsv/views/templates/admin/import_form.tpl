{*{extends file="helpers/view/view.tpl"}*}
{*{block name="override_tpl"}*}
{*    <div class="row mt-5 align-items-center">*}
{*        <div class="col-4 col-md-6 ml-5">*}
{*            <div class="card">*}
{*                <div class="card-header">*}
{*                    <h4 class="card-title font-italic serif mb-0">*}
{*                        {l s='Webimpacto Special Import' mod='importcsv'}*}
{*                    </h4>*}
{*                </div>*}
{*                <div class="card-body">*}
{*                    {if isset($error_message) && $error_message}*}
{*                        <div class="alert alert-danger alert-dismissible fade show" role="alert">*}
{*                            {$error_message}*}
{*                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">*}
{*                                <span aria-hidden="true">&times;</span>*}
{*                            </button>*}
{*                        </div>*}
{*                    {/if}*}
{*                    {if isset($success_message) && $success_message}*}
{*                        <div class="alert alert-success alert-dismissible fade show" role="alert">*}
{*                            {$success_message}*}
{*                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">*}
{*                                <span aria-hidden="true">&times;</span>*}
{*                            </button>*}
{*                        </div>*}
{*                    {/if}*}

{*                    <form action="{$form_action}" method="post" enctype="multipart/form-data" onsubmit="showLoading()" id="importForm">*}
{*                        <div class="custom-file">*}
{*                            <input*}
{*                                    type="file"*}
{*                                    class="custom-file-input"*}
{*                                    id="file3"*}
{*                            />*}
{*                            <label class="custom-file-label" for="file3">Choose files...</label>*}
{*                        </div>*}


{*                        <div class="form-group">*}
{*                            <button type="submit" name="submitImport" class="btn btn-primary" id="importBtn">*}
{*                                {l s='Import' mod='importcsv'}*}
{*                            </button>*}
{*                        </div>*}
{*                </div>*}
{*                    </form>*}
{*                </div>*}
{*                <div id="loadingModal" class="modal hidden">*}
{*                    <div class="modal-content modal-center">*}
{*                        <div class="spinner"></div>*}
{*                    </div>*}
{*                </div>*}
{*            </div>*}
{*        </div>*}
{*{/block}*}

<div class="container mt-5 ml-0 pl-0">
    <div class="row">
        <div class="col-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="https://global-uploads.webflow.com/62a20d704cfbf44f38550e4a/636a3626ff50fdb43311635f_webimpacto-logo-color.svg" alt="logo" style="max-width: 100%;">
                    </div>
                    {if isset($error_message) && $error_message}
                                           <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {$error_message}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                           </div>
                    {/if}
                    {if isset($success_message) && $success_message}
                                           <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                {$success_message}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                           </div>
                    {/if}
                    <div class="spinner-container d-none justify-content-center">
                        <div class="spinner spinner-primary"></div>
                    </div>
                    <form action="{$form_action}" method="post" enctype="multipart/form-data" onsubmit="showLoading()" id="importForm">
                        <div class="custom-file mb-3">
                            <input
                                    type="file"
                                    class="custom-file-input"
                                    name="fileInput"
                                    id="fileInput"
                            />
                            <label class="custom-file-label" for="fileInput">Choose a file...</label>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
