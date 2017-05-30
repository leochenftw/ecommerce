var FormOperator    =   function(id, firstname, surname, email, callback)
{
    this.template   =   '<form class="form-operator columns" method="POST" action="/storist/v1/manage/operators/OperatorForm">\
                            <div class="column">\
                                <div class="columns">\
                                    <div class="column">First name</div>\
                                    <div class="column">\
                                        <input id="operator-firstname" class="text" type="text" name="FirstName" required />\
                                    </div>\
                                </div>\
                                <div class="columns">\
                                    <div class="column">Surname</div>\
                                    <div class="column"><input id="operator-surname" class="text" type="text" name="Surname" /></div>\
                                </div>\
                                <div class="columns">\
                                    <div class="column">Email</div>\
                                    <div class="column"><input id="operator-email" class="text" type="email" name="Email" required /></div>\
                                </div>\
                                <div class="columns">\
                                    <div class="column">Password</div>\
                                    <div class="column">\
                                        <input class="text password-field" type="password" name="Password[_Password]"' + (id == null ? ' required' : '') + ' placeholder="Password" />\
                                        <input class="text password-field" type="password" name="Password[_ConfirmPassword]"' + (id == null ? ' required' : '') + ' placeholder="Confirm password" />\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="column is-4">\
                                <input id="operator-id" type="hidden" name="ID" value="" />\
                                <input class="button create action is-outlined is-info" type="submit" value="' + ((id == null) ? 'Create' : 'Update') + '" />\
                                <button class="button cancel action is-outlined is-danger">Cancel</button>\
                            </div>\
                        </form>';

    this.html     =     $(this.template);
    var refresh   =     function(data)
                        {
                            data = JSON.parse(data);

                            if (data.success) {
                                me.find('button.cancel').click();
                                $.get('/storist/v1/manage/operators', function(operator_list)
                                {
                                    $('.operator-list').html($(operator_list).find('.operator').OperatorWork());
                                });
                            }

                            var msg = new msgbox(data.message, !data.success ? 'Error' : 'Success');
                            msg.appendTo('body');
                        },
        me        =     this.html;

    if (id) {
        this.html.find('#operator-id').val(id);
        var btnDelete = $('<button />').addClass('button btn-delete is-outlined is-warning');
        btnDelete.html('Delete');
        btnDelete.insertAfter(this.html.find('.action.create'));
        btnDelete.click(function(e)
        {
            e.preventDefault();
            if (confirm('You sure you want to delete this operator?')) {
                $.post(
                    '/storist/v1/manage/operators/DeleteOperator',
                    {
                        ID: id
                    },
                    refresh
                );
            }
        });
    }

    if (firstname) {
        this.html.find('#operator-firstname').val(firstname);
    }

    if (surname) {
        this.html.find('#operator-surname').val(surname);
    }

    if (email) {
        this.html.find('#operator-email').val(email);
    }

    me.find('button.cancel').click(function(e)
    {
        e.preventDefault();
        if (callback) {
            callback();
        }
        me.remove();
    });

    me.ajaxSubmit(
    {
        success: refresh
    });

    return this.html;
};
