(function ($) {
    Drupal.behaviors.filter_color = {
        attach: function (context, settings) {
            $('.form-item-color-abazhura select, .form-item-color-karkas select, .description').hide();

            var applyHandlers = function (selectControl, targetBlock) {
                var clickHandle = function (event) {
                    event.preventDefault();
                    var color = $(this).data('value');

                    var $myControl = $(selectControl).find('option[value=' + color + ']');

                    if ($myControl.is(':selected')) {
                        $myControl.removeAttr('selected').change();
                        $(this).removeClass('select');
                    } else {
                        $myControl.attr('selected', 'selected').change();
                        $(this).addClass('select');
                    }
                };

                $(selectControl).find('option').each(function () {

                    var selectClass = $(this).is(':selected') ? ' class="select"' : '';

                    var val = $(this).val();
                    var hex = $(this).text().split('||')[0];
                    var title = $(this).text().split('||')[1];

                    $(targetBlock).append($('<a id="a-'+ $(selectControl).attr('id')+'-'+val+'" href="#"' + selectClass + ' title="'+title+'" style="background-color:' + hex + '" data-value="'+ val +'">' + val + '</a>').click(clickHandle));

                });
            };

            applyHandlers('.form-item-color-abazhura select', '.colors');
            applyHandlers('.form-item-color-karkas select', '.colors2');

            /* Подсказки фильтров */

            $('.filtertip').hover(function () {
                var span = $(this).find('.description');
                span.show();
                var area = span.width() * span.height();
                var newWidth = Math.sqrt(area);
                if (newWidth < 185) {
                    newWidth = 185
                }

                span.css({width: newWidth});
                span.css('top', -Math.round(span.outerHeight() / 2) + Math.round($(this).innerHeight() / 2));

               }, 

               function () {
                 var span = $(this).find('.description');
                    span.hide();
               });


            /* Ajax count result filter */
           
            $('.results').hide();

            var getFilterResultTimeout, filterChangeEmiter, getFilterResult = function () {
                var url = '/filter/get_count?' + $('#views-exposed-form-product-filter-page').serialize();
                var current_url = 'katalog/filter/result?' + $('#views-exposed-form-product-filter-page').serialize();
                $('.results').hide();
                var resultsContainer = filterChangeEmiter.closest('.views-exposed-widget').find('.results');
                $.ajax(url, {
                    beforeSend: function () {
                        resultsContainer.html('Обработка данных..');
                        resultsContainer.show();
                    },
                    success: function (resp) {
                       if (resp == 0) {
                            resultsContainer.html('Ничего не найдено<span>&nbsp;</span>');
                      } else {
                            resultsContainer.html('<a href="' + current_url + '">Показать ' + resp + ' товаров</a><span>&nbsp;</span>');
                       }

                   }
                })
            };


            $('#views-exposed-form-product-filter-page').find(':input').change(function () {
                filterChangeEmiter = $(this)
                getFilterResultTimeout && window.clearTimeout(getFilterResultTimeout);
                getFilterResultTimeout = window.setTimeout(getFilterResult, 300);
            });

            // Hide some filters

            $('.views-exposed-widget > label[for|="edit"]').wrap("<div class='title_widget'></div>");
                
            $('.views-exposed-widget > .title_widget').each(function() {
                if($(this).parent().find('.views-widget').is(':visible') || $(this).parent().find('input[type="checkbox"]').is(':checked') ) {
                       $(this).addClass('show');
                    }
                });
 
            $('.views-exposed-widget > .title_widget').click(function () {
                //$(this).parent().find('.views-widget').toggle('fast');
                 if ($(this).parent().find('.views-widget').is(":hidden")) {
                        $(this).addClass('show');
                        $(this).parent().find('.views-widget').show('fast');

                     } else {
                         $(this).removeClass('show');
                          //$(this).addClass('hide');
                         $(this).parent().find('.views-widget').hide('fast');
                      }
                 return false;
                });

            // Показываем иерархию "Категория" и отметим все дочерние термины
            
            $('ul.bef-tree input:checkbox').each(function() {
               // console.log($(this).parent());
               $ul = $(this).parent().parent().find('ul.bef-tree-child');
               $(this).is(':checked') ? $ul.show() : $ul.hide(); 
    
              });
            
             $('.bef-tree input:checkbox').change(function() {
              $ul = $(this).parent().parent().find('ul.bef-tree-child');
              //$res = $(this).parent().get(5);
              $res = $(this).parent().parent().parent().parent().parent().parent().find('.results');
              //console.log($ul.size());
                if ($(this).is(':checked')) {
                    if($ul.size() > 0 ) {
                        $ul.show('fast');
                        setTimeout('$res.html("Выберите подкатегорию<span>&nbsp;</span>");', 1500);
                    }
                 } else {
                   // Uncheck 
                   $(this).closest('li').find('input:checkbox').prop('checked', false);
                   $ul.hide('fast');
                 }
              });

            var placeholders = {
                'edit-count-lamp-min' : '1',     // Количество лампочек от
                'edit-count-lamp-max' : '21',    // Количество лампочек до
                'edit-dlina-min'      : '5',     // Длина от
                'edit-dlina-max'      : '160',     // Длина до
                'edit-shirina-min'    : '6',     // Ширина от
                'edit-shirina-max'    : '64',     // Ширина до
                'edit-diametr-min'    : '2,9',     // Диаметр от
                'edit-diametr-max'    : '108',     // Диаметр до
                'edit-ot-steny-min'   : '6',     // Выступ от
                'edit-ot-steny-max'   : '50',     // Выступ от
                'edit-height-min'     : '4',     // Высота от
                'edit-height-max'     : '320',     // Высота до
                /* Поле логина !Не трогать! */
                'login_name' : 'Введите логин'
            };

            $('input:text').each(function(i,el) {
                    if (!el.value || el.value == '') {
                        el.placeholder = placeholders[el.id] || '';
                    }
                });
            
            $('input:text').on('paste keyup', function() {
                 next = $(this).parent().next().find('input[type=text]');
                 id = next.attr('id');
                 (next.val() == '') ? next.val(placeholders[id]) : ''; 
                 $(this).change();
            });


        // Кнопка сбросить. Просто подменим урл, Get ничего не поймает и средиретит на current url (/katalog/filter)
         $('#edit-reset').on('click', function() {
               location.href ="/";
          });


        }
    };
})(jQuery);
