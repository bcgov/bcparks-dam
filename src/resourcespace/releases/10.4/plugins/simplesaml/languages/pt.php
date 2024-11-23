<?php


$lang["simplesaml_configuration"]='Configuração do SimpleSAML';
$lang["simplesaml_main_options"]='Opções de uso';
$lang["simplesaml_site_block"]='Utilize SAML para bloquear completamente o acesso ao site, se definido como verdadeiro, então ninguém pode acessar o site, mesmo anonimamente, sem autenticação';
$lang["simplesaml_allow_public_shares"]='Se o site estiver bloqueado, permitir que compartilhamentos públicos ignorem a autenticação SAML?';
$lang["simplesaml_allowedpaths"]='Lista de caminhos adicionais permitidos que podem ignorar o requisito SAML';
$lang["simplesaml_allow_standard_login"]='Permitir que os usuários façam login com contas padrão, bem como usando SAML SSO? AVISO: Desativar isso pode arriscar bloquear todos os usuários do sistema se a autenticação SAML falhar';
$lang["simplesaml_use_sso"]='Utilize SSO para fazer login';
$lang["simplesaml_idp_configuration"]='Configuração do IdP';
$lang["simplesaml_idp_configuration_description"]='Use o seguinte para configurar o plugin para funcionar com o seu IdP';
$lang["simplesaml_username_attribute"]='Atributo(s) a serem usados para o nome de usuário. Se for uma concatenação de dois atributos, por favor, separe com uma vírgula';
$lang["simplesaml_username_separator"]='Se unindo campos para o nome de usuário, use este caractere como separador';
$lang["simplesaml_fullname_attribute"]='Atributo(s) a ser(em) usado(s) para o nome completo. Se for uma concatenação de dois atributos, por favor, separe com uma vírgula';
$lang["simplesaml_fullname_separator"]='Se estiver juntando campos para o nome completo, use este caractere como separador';
$lang["simplesaml_email_attribute"]='Atributo a ser usado para o endereço de e-mail';
$lang["simplesaml_group_attribute"]='Atributo a ser usado para determinar a filiação ao grupo';
$lang["simplesaml_username_suffix"]='Sufixo a ser adicionado aos nomes de usuário criados para distingui-los das contas padrão do ResourceSpace';
$lang["simplesaml_update_group"]='Atualizar grupo de usuário a cada login. Se não estiver usando o atributo de grupo SSO para determinar o acesso, defina isso como falso para que os usuários possam ser movidos manualmente entre os grupos';
$lang["simplesaml_groupmapping"]='Mapeamento de Grupo do ResourceSpace - SAML';
$lang["simplesaml_fallback_group"]='Grupo de usuário padrão que será usado para usuários recém-criados';
$lang["simplesaml_samlgroup"]='Grupo SAML';
$lang["simplesaml_rsgroup"]='Grupo do ResourceSpace';
$lang["simplesaml_priority"]='Prioridade (um número mais alto terá precedência)';
$lang["simplesaml_addrow"]='Adicionar mapeamento';
$lang["simplesaml_service_provider"]='Nome do provedor de serviços local (SP)';
$lang["simplesaml_prefer_standard_login"]='Preferir login padrão (redirecionar para a página de login por padrão)';
$lang["simplesaml_sp_configuration"]='A configuração do simplesaml SP deve ser concluída para usar este plugin. Consulte o artigo da Base de Conhecimento para obter mais informações';
$lang["simplesaml_custom_attributes"]='Atributos personalizados para registrar no registro do usuário';
$lang["simplesaml_custom_attribute_label"]='Atributo SSO';
$lang["simplesaml_usercomment"]='Criado pelo plugin SimpleSAML';
$lang["origin_simplesaml"]='Plugin SimpleSAML';
$lang["simplesaml_lib_path_label"]='Caminho da biblioteca SAML (por favor, especifique o caminho completo do servidor)';
$lang["simplesaml_login"]='Utilizar credenciais SAML para fazer login no ResourceSpace? (Isso só é relevante se a opção acima estiver habilitada)';
$lang["simplesaml_create_new_match_email"]='Correspondência de e-mail: Antes de criar novos usuários, verifique se o e-mail do usuário SAML corresponde a um e-mail de conta RS existente. Se uma correspondência for encontrada, o usuário SAML irá \'adotar\' essa conta';
$lang["simplesaml_allow_duplicate_email"]='Permitir a criação de novas contas se já existirem contas do ResourceSpace com o mesmo endereço de e-mail? (isso será anulado se a correspondência de e-mail estiver definida acima e houver uma correspondência encontrada)';
$lang["simplesaml_multiple_email_match_subject"]='ResourceSpace SAML - tentativa de login conflitante com o email';
$lang["simplesaml_multiple_email_match_text"]='Um novo usuário SAML acessou o sistema, mas já existe mais de uma conta com o mesmo endereço de e-mail.';
$lang["simplesaml_multiple_email_notify"]='Endereço de e-mail para notificar se um conflito de e-mail for encontrado';
$lang["simplesaml_duplicate_email_error"]='Existe uma conta existente com o mesmo endereço de e-mail. Por favor, entre em contato com o seu administrador.';
$lang["simplesaml_usermatchcomment"]='Atualizado para usuário SAML pelo plugin SimpleSAML.';
$lang["simplesaml_usercreated"]='Criado novo usuário SAML';
$lang["simplesaml_duplicate_email_behaviour"]='Gerenciamento de contas duplicadas';
$lang["simplesaml_duplicate_email_behaviour_description"]='Esta seção controla o que acontece se um novo usuário SAML que faz login conflita com uma conta existente';
$lang["simplesaml_authorisation_rules_header"]='Regra de autorização';
$lang["simplesaml_authorisation_rules_description"]='Habilitar o ResourceSpace para ser configurado com autorização local adicional de usuários com base em um atributo extra (ou seja, afirmação/reivindicação) na resposta do IdP. Essa afirmação será usada pelo plugin para determinar se o usuário tem permissão para fazer login no ResourceSpace ou não.';
$lang["simplesaml_authorisation_claim_name_label"]='Nome do atributo (afirmação/reivindicação)';
$lang["simplesaml_authorisation_claim_value_label"]='Valor do atributo (afirmação/reivindicação)';
$lang["simplesaml_authorisation_login_error"]='Você não tem acesso a este aplicativo! Por favor, entre em contato com o administrador da sua conta!';
$lang["simplesaml_authorisation_version_error"]='IMPORTANTE: Sua configuração do SimpleSAML precisa ser atualizada. Consulte a seção \'<a href=\'https://www.resourcespace.com/knowledge-base/plugins/simplesaml#saml_instructions_migrate\' target=\'_blank\'>Migrando o SP para usar a configuração do ResourceSpace</a>\' da Base de Conhecimento para obter mais informações';
$lang["simplesaml_healthcheck_error"]='Erro do plugin SimpleSAML';
$lang["simplesaml_rsconfig"]='Usar arquivos de configuração padrão do ResourceSpace para definir a configuração do SP e metadados? Se isso estiver definido como falso, então a edição manual dos arquivos é necessária';
$lang["simplesaml_sp_generate_config"]='Gerar configuração SP';
$lang["simplesaml_sp_config"]='Configuração do Provedor de Serviços (SP)';
$lang["simplesaml_sp_data"]='Informação do Provedor de Serviços (PS)';
$lang["simplesaml_idp_section"]='Provedor de Identidade (IdP)';
$lang["simplesaml_idp_metadata_xml"]='Cole o XML de metadados do IdP';
$lang["simplesaml_sp_cert_path"]='Caminho para o arquivo de certificado SP (deixe em branco para gerar, mas preencha os detalhes do certificado abaixo)';
$lang["simplesaml_sp_key_path"]='Caminho para o arquivo de chave SP (.pem) (deixe em branco para gerar)';
$lang["simplesaml_sp_idp"]='Identificador do IdP (deixe em branco se estiver processando XML)';
$lang["simplesaml_saml_config_output"]='Cole este texto no arquivo de configuração do ResourceSpace';
$lang["simplesaml_sp_cert_info"]='Informação do certificado (obrigatório)';
$lang["simplesaml_sp_cert_countryname"]='Código do país (apenas 2 caracteres)';
$lang["simplesaml_sp_cert_stateorprovincename"]='Nome do estado, condado ou província';
$lang["simplesaml_sp_cert_localityname"]='Localidade (por exemplo, cidade)';
$lang["simplesaml_sp_cert_organizationname"]='Nome da organização';
$lang["simplesaml_sp_cert_organizationalunitname"]='Unidade organizacional / departamento';
$lang["simplesaml_sp_cert_commonname"]='Nome comum (por exemplo, sp.acme.org)';
$lang["simplesaml_sp_cert_emailaddress"]='Endereço de e-mail';
$lang["simplesaml_sp_cert_invalid"]='Informação de certificado inválida';
$lang["simplesaml_sp_cert_gen_error"]='Incapaz de gerar certificado';
$lang["simplesaml_sp_samlphp_link"]='Visite o site de teste do SimpleSAMLphp';
$lang["simplesaml_sp_technicalcontact_name"]='Nome do contato técnico';
$lang["simplesaml_sp_technicalcontact_email"]='E-mail de contato técnico';
$lang["simplesaml_sp_auth.adminpassword"]='Senha do administrador do site de teste SP';
$lang["simplesaml_acs_url"]='URL ACS / URL de resposta';
$lang["simplesaml_entity_id"]='Identificador de Entidade/URL de Metadados';
$lang["simplesaml_single_logout_url"]='URL de logout único';
$lang["simplesaml_start_url"]='Iniciar/URL de login';
$lang["simplesaml_existing_config"]='Siga as instruções da Base de Conhecimento para migrar sua configuração SAML existente';
$lang["simplesaml_test_site_url"]='URL do site de teste do SimpleSAML';
$lang["plugin-simplesaml-title"]='SAML Simples';
$lang["plugin-simplesaml-desc"]='[Avançado] Requer autenticação SAML para acessar o ResourceSpace';