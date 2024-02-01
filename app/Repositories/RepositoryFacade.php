<?php
/**
 * @package app.treq.school
 */
/** 
 * Facade for RepositoryFactory
 */
namespace App\Repositories;

class RepositoryFacade
{
    protected static $config = [
        // Shared repositories
        'Attachments'       => 'App\Repositories\AttachmentRepo',
        'Comments'          => 'App\Repositories\CommentRepo',
        'Logs'              => 'App\Repositories\LogRepo',
        // Email repositories
        'EmailBatches'      => 'App\Repositories\Email\EmailBatchRepo',
        'EmailLogs'         => 'App\Repositories\Email\EmailLogRepo',
        'EmailTemplates'    => 'App\Repositories\Email\EmailTemplateRepo',
        // Person repositories
        'People'            => 'App\Repositories\Person\PersonRepo',
        'PersonSearch'      => 'App\Repositories\Person\PersonSearch',
        'PersonStudent'     => 'App\Repositories\Person\StudentRepo',
        'Addresses'         => 'App\Repositories\Person\AddressRepo',
        'Appointments'      => 'App\Repositories\Person\EdwAppointmentRepo',
        'Emails'            => 'App\Repositories\Person\EmailRepo',
        'Phones'            => 'App\Repositories\Person\PhoneRepo',
        // Budget repositories
        'Budgets'           => 'App\Repositories\Budget\EdwBudgetRepo',
        'BudgetSearch'      => 'App\Repositories\Budget\BudgetSearch',
        // Apply repositories
        'ApplyCycles'       => 'App\Repositories\Apply\CycleRepo',
        // Appreview repositories
        'Applications'      => 'App\Repositories\Appreview\ApplicationRepo',
        'ApCommittees'      => 'App\Repositories\Appreview\CommitteeRepo',
        'ApCourses'         => 'App\Repositories\Appreview\CourseRepo',
        'ApCoursework'      => 'App\Repositories\Appreview\CourseworkRepo',
        'ApCoursePaths'     => 'App\Repositories\Appreview\CoursePathRepo',
        'ApDeadlines'       => 'App\Repositories\Appreview\DeadlineRepo',
        'ApDeadlineTypes'   => 'App\Repositories\Appreview\DeadlineTypeRepo',
        'ApDecisionLookups' => 'App\Repositories\Appreview\DecisionLookupRepo',
        'ApDegrees'         => 'App\Repositories\Appreview\DegreeRepo',
        'ApDegreeLookups'   => 'App\Repositories\Appreview\DegreeLookupRepo',
        'ApExtReviewers'    => 'App\Repositories\Appreview\ExternalReviewerRepo',
        'ApGsXml'           => 'App\Repositories\Appreview\GsXmlRepo',
        'ApInterests'       => 'App\Repositories\Appreview\InterestRepo',
        'ApMaterials'       => 'App\Repositories\Appreview\MaterialRepo',
        'ApMaterialTypes'   => 'App\Repositories\Appreview\MaterialTypeRepo',
        'ApPrograms'        => 'App\Repositories\Appreview\ProgramRepo',
        'ApRecLookups'      => 'App\Repositories\Appreview\RecommendationLookupRepo',
        'ApRecommendations' => 'App\Repositories\Appreview\RecommendationRepo',
        'ApReviewers'       => 'App\Repositories\Appreview\ReviewerRepo',
        'ApRubrics'         => 'App\Repositories\Appreview\RubricRepo',
        'ApScores'          => 'App\Repositories\Appreview\ScoreRepo',
        'ApScoreItems'      => 'App\Repositories\Appreview\ScoreItemRepo',
        'Testscores'        => 'App\Repositories\Appreview\TestscoreRepo',
        // Student repositories
        'Students'          => 'App\Repositories\Student\StudentRepo',
        // Test Score repositories
        'TsAssessments'     => 'App\Repositories\TestScore\AssessmentRepo',
        'TsEvidence'        => 'App\Repositories\TestScore\EvidenceRepo',
        'TsFiles'           => 'App\Repositories\TestScore\FileRepo',
        'TsPersonMatch'     => 'App\Repositories\TestScore\PersonMatchRepo',
        'TsRequirements'    => 'App\Repositories\TestScore\RequirementRepo',
        'TsScores'          => 'App\Repositories\TestScore\ScoreRepo',
        'TsTests'           => 'App\Repositories\TestScore\TestRepo',
        // UW repository
        'Uw'                => 'App\Repositories\Uw\UwRepo',
        'UwQuarters'        => 'App\Repositories\Uw\QuartersRepo',
    ];
    protected static $factory;

    public static function __callStatic($name, $arguments)
    {
        return self::get($name);
    }

    public static function get($name)
    {
        if (self::$factory === null) {
            self::$factory = new RepositoryFactory(self::$config);
        }
        return self::$factory->get($name);
    }

    /**
     * Populate the PHPDoc type hints for IDE support
     * artisan tinker
     * Repo::typeHints();
     */
    public static function typeHints()
    {
        foreach (self::$config as $name => $class) {
            echo " * @method static $class $name\n";
        }
    }

    /**
     * List repository aliases
     * @param string $like
     * @return array
     */
    public static function ls($like = null)
    {
        if ($like) {
            $like = strtolower($like);
        }
        $out = [];
        foreach (self::$config as $alias => $class) {
            if ($like === null) {
                $out[] = "Repo::{$alias}()";
            } else {
                $lcalias = strtolower($alias);
                if (strpos($lcalias, $like) !== false) {
                    $out[] = "Repo::{$alias}()";
                }
            }
        }
        return $out;
    }

}
