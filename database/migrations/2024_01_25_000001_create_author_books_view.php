<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    public function up()
    {
        DB::statement('
            CREATE VIEW author_books_view AS
            SELECT
                a.id AS author_id,
                a.name AS author_name,
                JSON_ARRAYAGG(
                        JSON_OBJECT(
                                "id", b.id,
                                "title", b.title,
                                "publishing_companies", b.publishing_companies,
                                "edition", b.edition,
                                "year_publication", b.year_publication,
                                "price", b.price,
                                "subjects", subjects_json
                        )
                ) AS books
            FROM
                authors a
                    JOIN
                book_authors ba ON a.id = ba.author_id
                    JOIN
                books b ON ba.book_id = b.id
                    LEFT JOIN (
                    SELECT
                        bs.book_id,
                        JSON_ARRAYAGG(
                                JSON_OBJECT(
                                        "id", s.id,
                                        "description", s.description
                                )
                        ) AS subjects_json
                    FROM
                        book_subjects bs
                            JOIN
                        subjects s ON bs.subject_id = s.id
                    GROUP BY
                        bs.book_id
                ) bs ON b.id = bs.book_id
            GROUP BY
                a.id, a.name
            ORDER BY
                a.id;
        ');
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS author_books_view;');
    }
};
