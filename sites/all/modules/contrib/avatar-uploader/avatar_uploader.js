
/**
 * bind event when you need, 
 * do it in custom code
 */
Drupal.behaviors.avatar_uploader = {
  attach: function(context) {
    if (Drupal.settings.avatar_uid) {
      Drupal.settings.avatar_selector = Drupal.settings.avatar_selector || '.profile .user-picture a';
      var el = jQuery(Drupal.settings.avatar_selector);
      au.attach(el, Drupal.settings.avatar_uid, Drupal.t('Change avatar'));
    }
  }
}

jQuery.fn.centerMe = function() {
    this.css({
        'position': 'fixed',
        'left': '50%',
        'top': '50%'
    });
    this.css({
        'margin-left': -this.outerWidth() / 2 + 'px',
        'margin-top': -this.outerHeight() / 2 + 'px'
    });
    return this;
}
au = {
  panel:   null,
  
  Events: {
    upload: {},
    submit: {},
    cancel: {},
    presubmit: {},
    dosubmit: {}
  },
  
  //css options for panel
  _options: {},
  _uid : null,
  
  //helper function to attach an event to element
  attach: function(el, uid, label) {
    el = jQuery(el);
    if (el.size()) {
      if (label) {
        var txt = jQuery('<div/>').html(label).appendTo(el).addClass('edit-label').css({'position': 'relative', 'bottom': '0'});
        
        /**
        el.hover(function(){
          txt.animate({bottom: '26px'}, 150);
        }, function(){
          txt.animate({bottom: '0px'}, 150);
        });**/
      }

      el.click(
        function() {
          au.show(uid);
          return false;
        }
      );

      au.Events.submit._update_img = function(res) {
        var src = res.new_avatar;
        if (src.substr(0, 'http://'.length) != 'http://') {
          src = Drupal.settings.basePath + src;
        }
        el.find('img').attr('src', src);
      }
    }
  },
  setup: function(uid, options) {
    if (!au.panel) {
      au._options = options;
      au._uid = uid;
      au.panel = jQuery('<div class="avatar_uploader" />').css(jQuery.extend({'z-index': 999}, au._options)).appendTo(jQuery('body')).centerMe();
    }
  },
  
  show: function(uid, options) {
    if (uid) {
      au.setup(uid, options);
    }
    
    if (!au._uid) {
      au._uid = Drupal.settings.avatar_uid;
    }
    
    if (!au.panel.html()) {
      jQuery.post(Drupal.settings.basePath + 'au/panel', {uid: au._uid}, function(res) {
        au.panel.html(res).centerMe();
      });
    }
    
    au.panel.centerMe().show('fast');
  },
  
  close: function() {
    au.panel.hide('fast');
  },
  
  trigger: function(name, param) {
    if (name == 'dosubmit' || name == 'cancel') {
      //close panel
      au.close();
    }
    
    //trigger event
    jQuery.each(au.Events[name], function(){
      this(param);
    });
  }
};
