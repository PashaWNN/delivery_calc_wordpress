<form id="calculateForm">
            <div class="win">
                <h1>Расчёт стоимости доставки</h1>
                <p>Откуда:</p>
                <div class="field">
                    <input name="city" class="city" id="from" type="text" required placeholder="Город" autocomplete="on">
                </div>
                <div class="field">
                    <input name="derival" id="derival" type="checkbox">
                    <label for="derival">Забрать груз у отправителя</label>
                </div>
                <p>Куда:</p>
                <div class="field">
                    <input name="city" id="to" class="city" type="text" required placeholder="Город" autocomplete="on">
                </div>
                <div class="field">
                    <input name="arrival" id="arrival" type="checkbox">
                    <label for="arrival">Доставить груз до дверей</label>
                </div>
                <p hidden>Вес, кг:</p>
                <div class="field" hidden>
                    <input name="weight" id="weight" min="0" required type="number" value="<?php echo $weight; ?>" step="any">
                </div>
                <div class="field">
                    <input type="submit" id="calculate" value="Рассчитать"/>
                </div>
            </div>
        </form>
        <div class="win" id="results" hidden>
            <h1>Стоимость доставки <em id="r_from"></em>-<em id="r_to"></em></h1>
            <div class="lds-ellipsis" id="loading"><div></div><div></div><div></div><div></div></div>
            <p class="results" id="r_error"></p>
            <p class="results" id="r_price_title" hidden>Полная стоимость: <em id="r_price"></em></p>
            <p class="results" id="r_derival" hidden>Стоимость забора от адреса: <em id="r_derival_price"></em></p>
            <p class="results" id="r_intercity" hidden>Стоимость междугородней перевозки: <em id="r_intercity_price"></em></p>
            <p class="results" id="r_arrival" hidden>Стоимость доставки до адреса: <em id="r_arrival_price"></em></p>
        </div>
