<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230420132939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE besoin ALTER id_besoin DROP DEFAULT');
        $this->addSql('ALTER TABLE centre ALTER id_centre DROP DEFAULT');
        $this->addSql('ALTER TABLE commentaire ALTER id_commentaire DROP DEFAULT');
        $this->addSql('ALTER TABLE creneaux ALTER id_creneau DROP DEFAULT');
        $this->addSql('ALTER TABLE identifiant ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE likes ALTER id_like DROP DEFAULT');
        $this->addSql('ALTER TABLE messages ALTER id_message DROP DEFAULT');
        $this->addSql('ALTER TABLE post ALTER id_post DROP DEFAULT');
        $this->addSql('ALTER TABLE rdv ALTER id_rdv DROP DEFAULT');
        $this->addSql('ALTER TABLE traiter ALTER id_traiter DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER id_user DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE SEQUENCE rdv_id_rdv_seq');
        $this->addSql('SELECT setval(\'rdv_id_rdv_seq\', (SELECT MAX(id_rdv) FROM rdv))');
        $this->addSql('ALTER TABLE rdv ALTER id_rdv SET DEFAULT nextval(\'rdv_id_rdv_seq\')');
        $this->addSql('CREATE SEQUENCE users_id_user_seq');
        $this->addSql('SELECT setval(\'users_id_user_seq\', (SELECT MAX(id_user) FROM users))');
        $this->addSql('ALTER TABLE users ALTER id_user SET DEFAULT nextval(\'users_id_user_seq\')');
        $this->addSql('CREATE SEQUENCE post_id_post_seq');
        $this->addSql('SELECT setval(\'post_id_post_seq\', (SELECT MAX(id_post) FROM post))');
        $this->addSql('ALTER TABLE post ALTER id_post SET DEFAULT nextval(\'post_id_post_seq\')');
        $this->addSql('CREATE SEQUENCE messages_id_message_seq');
        $this->addSql('SELECT setval(\'messages_id_message_seq\', (SELECT MAX(id_message) FROM messages))');
        $this->addSql('ALTER TABLE messages ALTER id_message SET DEFAULT nextval(\'messages_id_message_seq\')');
        $this->addSql('CREATE SEQUENCE identifiant_id_seq');
        $this->addSql('SELECT setval(\'identifiant_id_seq\', (SELECT MAX(id) FROM identifiant))');
        $this->addSql('ALTER TABLE identifiant ALTER id SET DEFAULT nextval(\'identifiant_id_seq\')');
        $this->addSql('CREATE SEQUENCE besoin_id_besoin_seq');
        $this->addSql('SELECT setval(\'besoin_id_besoin_seq\', (SELECT MAX(id_besoin) FROM besoin))');
        $this->addSql('ALTER TABLE besoin ALTER id_besoin SET DEFAULT nextval(\'besoin_id_besoin_seq\')');
        $this->addSql('CREATE SEQUENCE centre_id_centre_seq');
        $this->addSql('SELECT setval(\'centre_id_centre_seq\', (SELECT MAX(id_centre) FROM centre))');
        $this->addSql('ALTER TABLE centre ALTER id_centre SET DEFAULT nextval(\'centre_id_centre_seq\')');
        $this->addSql('CREATE SEQUENCE commentaire_id_commentaire_seq');
        $this->addSql('SELECT setval(\'commentaire_id_commentaire_seq\', (SELECT MAX(id_commentaire) FROM commentaire))');
        $this->addSql('ALTER TABLE commentaire ALTER id_commentaire SET DEFAULT nextval(\'commentaire_id_commentaire_seq\')');
        $this->addSql('CREATE SEQUENCE likes_id_like_seq');
        $this->addSql('SELECT setval(\'likes_id_like_seq\', (SELECT MAX(id_like) FROM likes))');
        $this->addSql('ALTER TABLE likes ALTER id_like SET DEFAULT nextval(\'likes_id_like_seq\')');
        $this->addSql('CREATE SEQUENCE creneaux_id_creneau_seq');
        $this->addSql('SELECT setval(\'creneaux_id_creneau_seq\', (SELECT MAX(id_creneau) FROM creneaux))');
        $this->addSql('ALTER TABLE creneaux ALTER id_creneau SET DEFAULT nextval(\'creneaux_id_creneau_seq\')');
        $this->addSql('CREATE SEQUENCE traiter_id_traiter_seq');
        $this->addSql('SELECT setval(\'traiter_id_traiter_seq\', (SELECT MAX(id_traiter) FROM traiter))');
        $this->addSql('ALTER TABLE traiter ALTER id_traiter SET DEFAULT nextval(\'traiter_id_traiter_seq\')');
    }
}
