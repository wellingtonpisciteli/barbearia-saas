<footer class="footer">

    <div class="container">

        <div class="row gy-4">

            {{-- SOBRE --}}
            <div class="col-lg-4">

                <h6 class="footer-title">
                    {{ $barbearia->nome }}
                </h6>

                <p class="footer-text">

                    Sistema de agendamento online para clientes.

                </p>

            </div>

            {{-- CONTATO --}}
            <div class="col-lg-4">

                <h6 class="footer-title">
                    Contato
                </h6>

                <ul class="footer-list">

                    <li>
                        {{ $barbearia->telefone ?? '(44) 99999-9999' }}
                    </li>

                    <li>
                        {{ $barbearia->instagram ?? 'contato@fadeos.com' }}
                    </li>

                </ul>

            </div>

            {{-- ENDEREÇO --}}
            <div class="col-lg-4">

                <h6 class="footer-title">
                    Endereço
                </h6>

                <ul class="footer-list">

                    <li>
                        {{ $barbearia->endereco ?? 'Rua Exemplo, 123' }}
                    </li>

                    <li>
                        {{ $barbearia->cidade ?? 'Paranavaí - PR' }}
                    </li>

                </ul>

            </div>

        </div>

        <div class="footer-bottom">

            <small>
                © {{ date('Y') }} FadeOS • Todos os direitos reservados
            </small>

        </div>

    </div>

</footer>