<div class="ui-block">
    <h3 class="ui-block-heading">Dropzone</h3>
    <a target="_blank" href="http://www.dropzonejs.com" class="ui-block-description">http://www.dropzonejs.com</a>

    <samp class="cui-example-code-static">
        &lt;link rel="stylesheet" href="&#123;&#123; mix('/vendor/libs/dropzone/dropzone.css') &#125;&#125;"&gt;
        &lt;script src="&#123;&#123; mix('/vendor/libs/dropzone/dropzone.js') &#125;&#125;"&gt;&lt;/script&gt;
    </samp>

    <h4 class="ui-block-heading">Examples</h4>

    <div class="cui-example">
        <div>
            <form action="/upload" class="dropzone needsclick" id="dropzone-demo">
                <div class="dz-message needsclick">
                    Drop files here or click to upload
                    <span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                </div>
                <div class="fallback">
                    <input name="file" type="file" multiple>
                </div>
            </form>
        </div>

        <!-- Javascript -->
        <script>
            $(function() {
                $('#dropzone-demo').dropzone({
                    parallelUploads: 2,
                    maxFilesize:     50000,
                    filesizeBase:    1000,
                    addRemoveLinks:  true,
                });

                // Mock the file upload progress (only for the demo)
                //
                Dropzone.prototype.uploadFiles = function(files) {
                    var minSteps         = 6;
                    var maxSteps         = 60;
                    var timeBetweenSteps = 100;
                    var bytesPerStep     = 100000;
                    var isUploadSuccess  = Math.round(Math.random());

                    var self = this;

                    for (var i = 0; i < files.length; i++) {

                        var file = files[i];
                        var totalSteps = Math.round(Math.min(maxSteps, Math.max(minSteps, file.size / bytesPerStep)));

                        for (var step = 0; step < totalSteps; step++) {
                            var duration = timeBetweenSteps * (step + 1);

                            setTimeout(function(file, totalSteps, step) {
                                return function() {
                                    file.upload = {
                                        progress: 100 * (step + 1) / totalSteps,
                                        total: file.size,
                                        bytesSent: (step + 1) * file.size / totalSteps
                                    };

                                    self.emit('uploadprogress', file, file.upload.progress, file.upload.bytesSent);
                                    if (file.upload.progress == 100) {

                                        if (isUploadSuccess) {
                                            file.status =  Dropzone.SUCCESS;
                                            self.emit('success', file, 'success', null);
                                        } else {
                                            file.status =  Dropzone.ERROR;
                                            self.emit('error', file, 'Some upload error', null);
                                        }

                                        self.emit('complete', file);
                                        self.processQueue();
                                    }
                                };
                            }(file, totalSteps, step), duration);
                        }
                    }
                };
            });
        </script>
        <!-- / Javascript -->
    </div>
</div>
