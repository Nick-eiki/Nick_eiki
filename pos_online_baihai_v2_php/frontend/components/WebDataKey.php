<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 15-7-13
 * Time: 下午1:17
 */
namespace frontend\components;
class WebDataKey
{

	const WEB_VIEW_CACHE="WEB_VIEW_CACHE";

    /**
     * 用户cacheKey
     */
    const  USER_CACHE_KEY = "user_data_cache_userId";
    /**
     *班级cacheKey
     */
    const  CLASS_CACHE_KEY = "class_data_cache_classId";

    /**
     * 学校cacheKey
     */
    const  SCHOOL_CACHE_KEY = "school_data_cache_schoolId";

    /**
     *
     */
    const  TEACHER_GROUP_CACHE_KEY = "teagroup_data_cache_teagroupId";

    /**
     * 年级cacheKey
     */
    const  GRADE_CACHE_KEY = "grade_data_cache_schoolId";

    /**
     * 年级cacheKey
     */
    const  SHOWTYPE_CACHE_KEY = "show_type_data_cache_schoolId";

	/**
	 * 签到cacheKey
	 */
	const USER_IS_SIGN="user_is_sign_cache_userId";

    /**
     * 获取题目题号的cacheKey
     */
    const QUESTION_NO_OBJECT_KEY="managetask_cache_question_number";

    /**
     * 题目查询cacheKey
     */
    const  QUESTION_CHILDREN_LIST_KEY="question_cache_child_list_by_questionid";

    /**
     * 全文搜索cacheKey
     */
    const  SEARCH_QUESTION_CHILDREN_LIST_KEY="search_question_cache_child_list_by_questionid";

    /**
     * 通过id获取平台小题列表cacheKey
     */
    const  QUESTION_CHILDREN_PLATFORM_LIST_KEY="question_cache_platform_child_list_by_questionid";

	/**
	 *班级首页 数据统计 cacheKey
	 */
	const  WEB_CLASS_VIEW_CACHE_KEY = "WEB_VIEW_CACHE_CLASS_VIEW_BY_";

	/**
	 * 班级首页 班级成员 cacheKey
	 */
	const WEB_CLASS_VIEW_MEMBER_CACHE_KEY = "WEB_CLASS_VIEW_MEMBER_CACHE_BY_";

	/**
	 * 班级 教师班级作业列表学科列表 片段缓存 cacheKey
	 */
	const WEB_CLASS_TEACHER_HOMEWORK_CACHE_KEY = "WEB_CLASS_TEACHER_HOMEWORK_CACHE_BY_";

	/**
	 * 教师班级作业 详情页作业简介片段缓存 cacheKey
	 */
	const WEB_CLASS_WORK_dETAILS_CACHE_KEY = "WEB_CLASS_WORK_DETAILS_CACHE_BY_";
	/**
	 * 教师个人中心 我的文件 cacheKey
	 */
	const WEB_TEACHER_PERSONAL_CENTER_MY_FILES_CACHE_KEY = "WEB_TEACHER_PERSONAL_CENTER_MY_FILES_VIEW_BY_";

	/**
	 * 教师个人中心 我的收藏 cacheKey
	 */
	const WEB_TEACHER_PERSONAL_CENTER_MY_FAVORITE_CACHE_KEY = "WEB_TEACHER_PERSONAL_CENTER_MY_FAVORITE_VIEW_BY_";

    const WEB_TEACHER_PERSONAL_STATISTICS_CACHE_KEY = 'WEB_TEACHER_PERSONAL_STATISTICS_CACHE_KEY';

	/**
	 * 教师个人中心 我的作业 cacheKey
	 */
	const WEB_TEACHER_PERSONAL_CENTER_MY_HOMEWORK_CACHE_KEY = "WEB_TEACHER_PERSONAL_CENTER_MY_HOMEWORK_VIEW_BY_";

	/**
	 * 学生个人中心 我的作业 cacheKey
	 */
	const WEB_STUDENT_MY_CENTER_MY_HOMEWORK_CACHE_KEY = "WEB_STUDENT_MY_CENTER_MY_HOMEWORK_VIEW_BY_";

    /**
     *  subjectModel  根据学部获取科目缓存
     */
    const SUBJECT_DATA_BY_DEPARTMENT_KEY='subject_data_by_department';

    /**
     *SeUserinfo 获取用户所在班级
     */
    const CLASS_INFO_DATA_BY_USERID_KEY="class_info_data_by_userID";

    /**
     * SeHomeworkRel  获取已答学生数
     */
    const HOMEWORK_ANSWER_INFO_COUNT_KEY="homework_answer_info_count";

    /**
     * QuestionInfoHelper
     *   题目详细
     */
    const HOMEWORK_GET_QUESTION_DATA_BY_ID_KEY="homework_get_question_data_by_id";

    /**
     * ShTestquestion 判断是主观题还是客观题
     */
    const IS_MAJOR_QUESTION_BY_TQTID_KEY="is_major_question_by_tqtid";

    /**
     *学生作业答题页面缓存
     */
    const WEB_STUDENT_ANSWERING_QUESTION_LIST_KEY="web_student_answering_question_list";
    /**
     *   SeHomeworkRel  获取已批改学生数
     */
    const IS_CHECKED_STUDENT_COUNT_KEY="is_checked_student_count";

    /**
     *判断用户是否在班级中
     */
    const USER_IS_IN_CLASS_KEY="user_is_in_class";

    /**
     *判断用户是否在教研组中
     */
    const USER_IS_IN_GROUP_KEY="user_is_in_group";

    /**
     *找回密码 cacheKey
     */
    const RESETPHONEMESSAGE = "RESETPHONEMESSAGE_";

	/**
	 * 平台作业分配给老师的所有记录
	 */
	const PLATFORM_HOMEWORK_TEACHER = 'paltform_homework_teacher';
	/**
	 * 作业回答总人数
	 */
	const FINISH_HOMEWORK_KEY = 'finish_homework_key';
	/**
	 *学生所在当前梯队前面有多少人
	 */
	const OVER_HOMEWORK_NOWUSER = 'over_homework_nowuser';

	/**
	 * 学生打完作业后的梯队展示
	 */
	const  HOMEWORK_ANSWER_TEAMDATA_SHOW = "homework_answer_teamdata_show";

	/**
	 *班级学生人数 cacheKey
	 */
	const  CLASS_STUDENT_MEMBER_CACHE_KEY = "class_student_member_data_cache_classId";
	/*
	 *科目
	 */
	const CLASS_SUBJECT_ID_CACHE_KEY = 'class_subject_id_cache_key';
	/*
	 * 学段
	 */
	const CLASS_DEPARTMENT_ID_CACHE_KEY = 'class_department_id_cache_key';

	/*
	 * 激活人数
	 */
	const ACTIVATESUM_CACHE_KEY = 'activateSum_cache_key';
	/*
	 * 学校注册总人数
	 */
	const PEOPLESUM_CACHE_KEY = 'peopleSum_cache_key';
	/*
	 * 家长激活人数
	 */
	const HOMEREGISTERSUM_CACHE_KEY = 'homeRegisterSum_cache_key';
	/*
	 * 作业使用统计
	 */
	const HOMEWORKUSESUM_CACHE_KEY = 'homeworkuseSum_cache_key';

    /*
     * 课件分组下的课件数
     */
    const  GROUP_MATERAIL_NUM_CACHE_KEY = 'group_material_num_cache_key';

    /*
     * 学校短板统计
     */
    const SCHOOL_SHORTBOARD_CACHE_KEY = 'school_shortboard_cache_key';

	/**
	 * 班级班主任
	 */
	const CLASS_ADVISER_CACHE_KEY = 'class_adviser_class_id';

	/**
	 * 班级教师列表
	 */
	const CLASS_TEACHER_LIST_CACHE_KEY = 'class_teacher_list_class_id';

	/**
	 * 班级学生列表
	 */

	const CLASS_STUDENT_LIST_CACHE_KEY = 'class_student_list_class_id';

	/**
	 * 页面顶导 通知数字
	 */
	const TOP_NAV_MSG_NUM_CACHE_KEY = 'top_nav_msg_num_user_id';

}