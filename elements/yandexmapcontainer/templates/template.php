<?php
$mapItems = $children;
?>

<div class="element-map">
    <div class="map-card-wrap">
        <div class="card-for-map">
            <h3><?= $props['title'] ?></h3>
            <div class="map-location-switcher">
                <ul data-uk-tab="animation: uk-animation-fade">
                    <?php foreach ($mapItems as $item) : ?>
                        <?php $propsItem = $item->props ?>
                        <li><a href="#"><?= $propsItem['title'] ?></a></li>
                    <?php endforeach; ?>
                </ul>

                <ul class="uk-switcher uk-margin" uk-height-match="target: > li;row: false">
                    <?php $i = 0; ?>
                    <?php foreach ($children as $child) : ?>
                        <li data-id="<?php echo $i?>">
                            <?= $builder->render($child, ['element' => $props]) ?>
                        </li>
                        <?php $i++; ?>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="map-wrap">
        <div class="uk-container uk-hidden@s">
            <p class="uk-text-center uk-margin-top uk-margin-bottom">Передвинуть карту можно двумя пальцами</p>
        </div>
        <div class="yandex-map-wrap" data-uk-scrollspy="target: div; delay: 100; repeat: false"><div id="YMapsID" class="uk-img-preserve yandex-map"></div></div>
    </div>
</div>


<script type="text/javascript">
    var map;
    var flag = true;

    UIkit.util.ready(function() {
        UIkit.util.on('.yandex-map-wrap', 'inview', function() {
            if(!flag) { return false; }
            flag = false;
            var tag = document.createElement("script");
            tag.src = "https://api-maps.yandex.ru/2.1/?lang=ru-RU&amp;coordorder=latlong";
            tag.onload = function() {
                ymaps.ready(function () {
                    var isMobile = window.matchMedia("only screen and (max-width: 968px)");
                    var points_center;
                    var points_office;
                    var points = [];
                    var offices = [];

                    <?php foreach ($mapItems as $item) : ?>
                    <?php $propsItem = $item->props ?>
                    points.push(
                        {
                            'desktop': [<?php echo $propsItem['desktop_points_center'] ?>],
                            'mobile': [<?php echo $propsItem['mobile_points_center'] ?>],
                            'office': [<?php echo $propsItem['points_office'] ?>]
                        });
                    <?php endforeach; ?>


                    if(!isMobile.matches) {
                        points_center = points[0].desktop;
                    } else {
                        points_center = points[0].mobile;
                    }

                    map = new ymaps.Map("YMapsID", {
                            center: points_center,
                            zoom: 16,
                            maxZoom: 17,
                            minZoom: 10,
                            type: "yandex#map",
                            controls: [],
                            behaviors: ["default"]
                        }
                    );

                    UIkit.util.on('.element-map .uk-switcher', 'show', function (ev, ar) {
                        var i_current = parseInt(ev.target.getAttribute('data-id'));

                        if(points[i_current].desktop.length > 0) {
                            if(!isMobile.matches) {
                                points_center = points[i_current].desktop;
                            } else {
                                points_center = points[i_current].mobile;
                            }

                            if(!isMobile.matches) {
                                offices[i_current].balloon.open();
                            }

                            map.setCenter(points_center);
                        }


                    });

                    map.behaviors.disable('scrollZoom');
                    if(isMobile.matches) {
                        map.behaviors.disable('drag');
                    }

                    map.controls.add('zoomControl', {
                        size: 'auto'
                    });


                    var iconImageHref = "images/icons/formap.svg";
                    var iconImageSize = [50, 50];
                    var iconImageOffset = [-50, -50];

                    for(let i=0;i<points.length;i++) {
                        offices.push(new ymaps.Placemark(
                            points[i].office, {
                                balloonContent: '<?= $props['placemark'] ?>'
                            }, {
                                iconLayout: 'default#image',
                                iconImageHref: iconImageHref,
                                iconImageSize: iconImageSize,
                                iconImageOffset: iconImageOffset
                            },
                            {
                                draggable: false,
                                hideIconOnBalloonOpen: true
                            }
                        ));

                    }

                    for(let i=0;i<offices.length;i++) {
                        map.geoObjects.add(offices[i]);
                    }


                    var i_current = 0;
                    if(!isMobile.matches) {
                        points_center = points[i_current].desktop;
                    } else {
                        points_center = points[i_current].mobile;
                    }
                    if(!isMobile.matches) {
                        offices[i_current].balloon.open();
                    }
                    map.setCenter(points_center);
                });
            };
            document.querySelector("head").appendChild(tag);


        });
    });
</script>