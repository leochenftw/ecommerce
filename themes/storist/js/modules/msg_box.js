var msgbox          =   function(message, title)
{
    title           =   title ? title : 'Message';

    this.template   =   '<div class="modal is-active">\
                          <div class="modal-background"></div>\
                          <div class="modal-card">\
                            <header class="modal-card-head">\
                              <p class="modal-card-title">' + title + '</p>\
                              <button class="delete"></button>\
                            </header>\
                            <section class="modal-card-body">' + message + '</section>\
                            <footer class="modal-card-foot has-text-centered">\
                              <a class="button is-success close">OK</a>\
                            </footer>\
                          </div>\
                        </div>';
    this.html       =   $(this.template);
    var me          =   this.html;

    this.html.find('button.delete, .button.close, .modal-background').click(function(e)
    {
        e.preventDefault();
        me.remove();
    });

    return this.html;
};
