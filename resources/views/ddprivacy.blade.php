<x-app-layout>
    <div class="mx-auto my-16 max-w-7xl px-6 mt-24 lg:px-8">
        <a href="{{ route('about') }}"
            class="-mt-10 mb-12 flex items-center dark:text-slate-400 text-slate-600 hover:underline" wire:navigate>
            <x-icons.chevron-left class="size-4" />
            <span>{{ __('Back') }}</span>
        </a>

        <div class="mt-6">
            <div class="prose prose-slate dark:prose-invert mx-auto max-w-4xl">
                <h1>Polityka prywatności</h1> 
                <p><strong>Aktualizacja: 09-10-2024</strong></p>

                <p>
                    Witamy w WildCamping.pl. Jesteśmy zobowiązani do ochrony Twoich danych osobowych i Twojego prawa do
                    prywatności. Jeśli masz
                    jakiekolwiek pytania lub wątpliwości dotyczące niniejszej informacji o ochronie prywatności lub
                    naszych praktyk dotyczących
                    Twoich danych osobowych, skontaktuj się z nami pod adresem <a href="mailto:team@wildcamping.pl">team@wildcamping.pl</a>.
                </p>

                <ul class="list-none">
                    <li>
                        <h2>1. Informacje ogólne</h2>
                        <ul class="list-none">
                            <li>1.1. Niniejsza polityka prywatności określa zasady przetwarzania danych osobowych na stronie
                                {{ config('app.name', 'WildCamping.pl') }} (dalej „Strona”).</li>
                            <li>1.2. Operatorem serwisu oraz Administratorem danych osobowych jest Szymon Gołaszewski ul. Stawigudzka 7/136 (dalej „Administratorem”).</li>
                            <li>1.3. Kontakt z Administratorem możliwy jest poprzez adres e-mail: <a
                                    href="mailto:team@wildcamping.pl">team@wildcamping.pl</a>.</li>
                            <li>1.4. Operator jest Administratorem Twoich danych osobowych w odniesieniu do danych podanych dobrowolnie w Serwisie.</li>
                            <li>1.5. Serwis wykorzystuje dane osobowe w następujących celach:<br />
                                <ul>
                                    <li>Świadczenie usług drogą elektroniczną</li>
                                    <li>Realizacja zamówień</li>
                                    <li>Kontakt z użytkownikami</li>
                                    <li>Marketing bezpośredni własnych produktów i usług</li>
                                    <li>Analiza statystyczna ruchu na stronie</li>
                                    <li>Zapewnienie bezpieczeństwa serwisu.</li>
                                </ul>
                            </li>
                            <li>1.6. Serwis realizuje funkcje pozyskiwania informacji o użytkownikach i ich zachowaniu w następujący sposób:<br />
                                <ul>
                                    <li>Poprzez dobrowolnie wprowadzone w formularzach dane, które zostają wprowadzone do systemów Operatora.</li>
                                    <li>Poprzez zapisywanie w urządzeniach końcowych plików cookie (tzw. „ciasteczka”).</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <h2>2. Wybrane metody ochrony danych stosowane przez Operatora</h2>
                        <ul class="list-none">
                            <li>2.1. Miejsca logowania i wprowadzania danych osobowych są chronione w warstwie transmisji (certyfikat SSL). Dzięki temu dane osobowe i dane logowania, wprowadzone na stronie, zostają zaszyfrowane w komputerze użytkownika i mogą być odczytane jedynie na docelowym serwerze.</li>
                            <li>2.2. Hasła użytkowników są przechowywane w postaci hashowanej. Funkcja hashująca działa jednokierunkowo - nie jest możliwe odwrócenie jej działania, co stanowi obecnie współczesny standard w zakresie przechowywania haseł użytkowników.</li>
                            <li>2.3. W serwisie jest stosowana autentykacja dwuskładnikowa, co stanowi dodatkową formę ochrony logowania do Serwisu.</li>
                            <li>2.4. Operator okresowo zmienia swoje hasła administracyjne.</li>
                        </ul>
                    </li>
                    <li>
                        <h2>3. Hosting</h2>
                        <ul class="list-none">
                            <li>3.1. Zebrane dane osobowe są przetwarzane w następujących celach:
                                <ul>
                                    <li>Świadczenie usług drogą elektroniczną</li>
                                    <li>Realizacja zamówień</li>
                                    <li>Kontakt z użytkownikami</li>
                                    <li>Marketing bezpośredni własnych produktów i usług</li>
                                    <li>Analiza statystyczna ruchu na stronie</li>
                                    <li>Zapewnienie bezpieczeństwa serwisu.</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <h2>4. Podstawy prawne przetwarzania danych</h2>
                        <ul class="list-none">
                            <li>4.1. Dane osobowe przetwarzane są zgodnie z:<br /> Rozporządzeniem Parlamentu Europejskiego i
                                Rady (UE) 2016/679 z dnia 27 kwietnia 2016 r. w sprawie ochrony osób fizycznych w
                                związku z przetwarzaniem danych osobowych (RODO) Ustawą z dnia 10 maja 2018 r. o
                                ochronie danych osobowych Inne obowiązujące przepisy prawa krajowego i unijnego
                                dotyczące ochrony danych.</li>
                        </ul>
                    </li>
                    <li>
                        <h2>5. Udostępnianie danych</h2>
                        <ul class="list-none">
                            <li>5.1. Dane osobowe mogą być udostępniane następującym odbiorcom:<br /> Podmioty przetwarzające
                                dane na zlecenie Administratora (np. dostawcy usług IT) Organy publiczne, jeśli wymagają
                                tego obowiązujące przepisy prawa Inne podmioty wyłącznie za zgodą użytkownika.</li>
                        </ul>
                    </li>
                    <li>
                        <h2>6. Prawa użytkowników</h2>
                        <ul class="list-none">
                            <li>6.1. Użytkownik ma prawo do:
                                <ul>
                                    <li>Dostępu do swoich danych osobowych</li>
                                    <li>Sprostowania nieprawidłowych danych</li>
                                    <li>Usunięcia danych (prawo do bycia zapomnianym)</li>
                                    <li>Ograniczenia przetwarzania danych</li>
                                    <li>Przenoszenia danych</li>
                                    <li>Wniesienia sprzeciwu wobec przetwarzania danych</li>
                                    <li>Cofnięcia zgody na przetwarzanie danych osobowych w dowolnym momencie (jeśli
                                        przetwarzanie odbywa się na podstawie zgody).</li>
                                </ul>
                            </li>
                            <li>6.2. W celu realizacji swoich praw użytkownik może skontaktować się z Administratorem poprzez
                                e-mail: <a href="mailto:team@wildcamping.pl">team@wildcamping.pl</a>.</li>
                        </ul>
                    </li>

                    <li>
                        <h2>7. Okres przechowywania danych</h2>
                        <ul class="list-none">
                            <li>7.1. Dane osobowe będą przechowywane przez okres niezbędny do realizacji celów przetwarzania
                                określonych w punkcie 3 lub do momentu wycofania zgody przez użytkownika.</li>
                        </ul>
                    </li>
                    <li>
                        <h2>8. Bezpieczeństwo danych</h2>
                        <ul class="list-none">
                            <li>8.1. Administrator stosuje odpowiednie środki techniczne i organizacyjne, aby zapewnić
                                ochronę danych osobowych przed ich przypadkowym lub niezgodnym z prawem zniszczeniem,
                                utratą, modyfikacją, nieuprawnionym ujawnieniem lub dostępem.</li>
                        </ul>
                    </li>

                    <li>
                        <h2>9. Zmiany w polityce prywatności</h2>
                        <ul class="list-none">
                            <li>9.1. dministrator zastrzega sobie prawo do wprowadzania zmian w niniejszej polityce
                                prywatności. Zmiany będą publikowane na Stronie.</li>
                            <li>9.2. Zaleca się regularne sprawdzanie polityki prywatności w celu zapoznania się z jej
                                aktualną wersją. </li>
                        </ul>
                    </li>

                    <li>
                        <h2>10. Kontakt</h2>
                        <ul class="list-none">
                            <li>10.1. W przypadku pytań dotyczących niniejszej polityki prywatności, prosimy o kontakt
                                na adres e-mail: <a href="mailto:team@wildcamping.pl">team@wildcamping.pl</a>.
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
