<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><strong>{lang}Send HTML Mail{/lang}</strong></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{$form_action}" method="post" role="form">
                <div class="box-body">
                    {if empty($error_message) === false}
                        <p style="border: 1px solid #CC0000; background: #FFAA00; color:#CC0000; padding: 5px; ">
                            {$error_message}
                        </p>
                    {/if}

                    {if empty($success_message) === false}
                        <p style="border: 1px solid green; background: yellowgreen; color:green; padding: 5px; ">{$success_message}</p>
                    {/if}

                    <div class="form-group">
                        <label for="to_mail">{lang}Receiver{/lang}</label>
                        <input type="email" name="to_mail" class="form-control" id="to_mail" placeholder="info@example.com" required="required">
                    </div>
                    <div class="form-group">
                        <label for="subject">{lang}Subject{/lang}</label>
                        <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject" required="required">
                    </div>

                    <div class="form-group">
                    <textarea id="compose-textarea" class="form-control" style="height: 300px">
                      <h1><u>Heading Of Message</u></h1>
                      <h4>Subheading</h4>
                      <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                        was born and I will give you a complete account of the system, and expound the actual teachings
                        of the great explorer of the truth, the master-builder of human happiness. No one rejects,
                        dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know
                        how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again
                        is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain,
                        but because occasionally circumstances occur in which toil and pain can procure him some great
                        pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise,
                        except to obtain some advantage from it? But who has any right to find fault with a man who
                        chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that
                        produces no resultant pleasure? On the other hand, we denounce with righteous indignation and
                        dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so
                        blinded by desire, that they cannot foresee</p>
                      <ul>
                        <li>List item one</li>
                        <li>List item two</li>
                        <li>List item three</li>
                        <li>List item four</li>
                      </ul>
                      <p>Thank you,</p>
                      <p>John Doe</p>
                    </textarea>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> {lang}Send{/lang}</button>
                    </div>

                    <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> {lang}Discard{/lang}</button>
                </div>
            </form>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->